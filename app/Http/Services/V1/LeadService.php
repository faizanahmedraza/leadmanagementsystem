<?php

namespace App\Http\Services\V1;

use App\Exceptions\V1\ModelException;
use App\Exceptions\V1\FailureException;

use App\Exceptions\V1\RequestValidationException;
use App\Models\Lead;
use App\Models\LeadComment;
use App\Models\User;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use App\Helpers\TimeStampHelper;
use Illuminate\Support\Facades\DB;
use mysql_xdevapi\Exception;

class LeadService
{
    /**
     *  Create new Lead via required parameters. It will return Lead Object as Response
     *
     * @Param first_name
     * @Param last_name
     * @Param password
     * @Param email
     *
     *
     */
    public static function store(Request $request)
    {
        $lead = new Lead;
        $lead->name = $request->name;
        $lead->description = serialize($request->description);
        $lead->phone = $request->phone;
        $lead->email = clean($request->email);
        $lead->address = $request->email;

        if (isset($request->status)) {
            $lead->status = Lead::STATUS[$request->status];
        } else {
            $lead->status = Lead::STATUS['pending'];
        }

        $lead->created_by = Auth::id();
        $lead->save();

        if (!$lead) {
            throw FailureException::serverError();
        }

        return $lead;
    }

    public static function get(Request $request)
    {
        $leads = Lead::query()->withCount('leadUsers');

        if ($request->has("leads")) {
            $ids = \getIds($request->leads);
            $leads->orWhereIn('id', $ids);
        }

        if ($request->has('name')) {
            $name = strtolower($request->name);
            $leads->whereRaw("LOWER(name) = ? ", $name);
        }

        if ($request->has('email')) {
            $email = clean($request->email);

            if (is_array($email)) {
                $leads->whereRaw("TRIM(LOWER(email)) in  ('" . join("', '", $email) . "')");
            } else {
                $leads->whereRaw('TRIM(LOWER(email)) = ?', $email);
            }
        }

        if ($request->has('status')) {
            $arrStatus = getStatus(Lead::STATUS, clean($request->status));
            $leads->whereIn('status', $arrStatus);
        }

        if ($request->query('order_by')) {
            $leads->orderBy('id', $request->get('order_by'));
        } else {
            $leads->orderBy('id', 'desc');
        }

        if ($request->has('from_date')) {
            $from = TimeStampHelper::formateDate($request->from_date);
            $leads->whereDate('created_at', '>=', $from);
        }

        if ($request->has('to_date')) {
            $to = TimeStampHelper::formateDate($request->to_date);
            $leads->whereDate('created_at', '<=', $to);
        }

        return ($request->filled('pagination') && $request->get('pagination') == 'false')
            ? $leads->get()
            : $leads->paginate(\pageLimit($request));

    }

    public static function first(int $id, $with = ['leadUsers', 'comments'])
    {
        $lead = Lead::with($with)
            ->where('id', $id)
            ->first();

        if (!$lead) {
            throw ModelException::dataNotFound();
        }

        return $lead;
    }

    public static function update($request, Lead $lead)
    {
        $lead->name = $request->name;
        $lead->description = serialize($request->description);
        $lead->phone = $request->phone;
        $lead->email = $request->email;
        $lead->address = $request->email;

        if (isset($request->status)) {
            $lead->status = Lead::STATUS[$request->status];
        }

        $lead->updated_by = Auth::id();
        $lead->save();

        if (!$lead) {
            throw FailureException::serverError();
        }

        return $lead;
    }

    public static function destroy(Lead $lead)
    {
        $lead->delete();
    }

    public static function toggleStatus(Lead $lead, Request $request)
    {
        $lead->status = Lead::STATUS[trim(strtolower($request->status))];
        $lead->save();
    }

    public static function getLeads(array $ids, $with = ['leadUsers'])
    {
        $leads = Lead::with($with)
            ->whereIn('id', $ids)
            ->get();

        if (!$leads) {
            throw ModelException::dataNotFound();
        }

        return $leads;
    }

    public static function assignLeads(User $user, Request $request)
    {
        $timestamp = date('Y-m-d H:i:s');
        $user->assignLeads()->syncWithPivotValues($request->leads, ['created_by' => Auth::id(), 'updated_by' => Auth::id(), 'created_at' => $timestamp, 'updated_at' => $timestamp]);
    }

    public static function comments(Request $request, Lead $lead)
    {
        $comment = new LeadComment;
        $comment->description = serialize($request->description);
        $comment->user_id = Auth::id();
        $lead->comments()->save($comment);
    }

    public static function getComments($id)
    {
        return LeadComment::with('user')->where('lead_id', $id)->latest()->get();
    }

    public static function uploadCsv(Request $request)
    {
        $file = $request->file('csv_file');
        if ($file) {
            $filename = $file->getClientOriginalName();
            $extension = $file->getClientOriginalExtension(); //Get extension of uploaded file
            $fileSize = $file->getSize(); //Get size of uploaded file in bytes
            //Check for file extension and size
            self::checkUploadedFileProperties($extension, $fileSize);
            //Where uploaded file will be stored on the server
            $location = 'uploads'; //Created an "uploads" folder for that
            // Upload file
            $file->move($location, $filename);
            // In case the uploaded file path is to be stored in the database
            $filepath = public_path($location . "/" . $filename);
            // Reading file
            $file = fopen($filepath, "r");
            $importData_arr = array(); // Read through the file and store the contents as an array
            $i = 0;
            //Read the contents of the uploaded file
            while (($filedata = fgetcsv($file, 1000, ",")) !== FALSE) {
                $num = count($filedata);
                // Skip first row (Remove below comment if you want to skip the first row)
                if ($i == 0) {
                    $i++;
                    continue;
                }
                for ($c = 0; $c < $num; $c++) {
                    $importData_arr[$i][] = $filedata[$c];
                }
                $i++;
            }
            fclose($file); //Close after reading
            $readyData = [];
            foreach ($importData_arr as $key => $importData) {
                $readyData[$key][] = [
                    'name' => $importData[0] ?? null,
                    'description' => $importData[1] ?? null,
                    'phone' => $importData[2] ?? null,
                    'email' => $importData[3] ?? null,
                    'address' => $importData[4] ?? null,
                    'status' => $importData[5] ?? null,
                    'created_by' => Auth::id(),
                    'created_at' => TimeStampHelper::getTimestampByFormat(),
                ];
            }
            try {
                DB::table('leads')->insert($readyData);
            } catch (\Exception $e) {
                DB::rollBack();
            }
        } else {
            RequestValidationException::errorMessage('No file was uploaded.', 422);
        }
    }

    public static function checkUploadedFileProperties($extension, $fileSize)
    {
        $valid_extension = array("csv", "xlsx"); //Only want csv and excel files
        $maxFileSize = 2097152; // Uploaded file size limit is 2mb
        if (in_array(strtolower($extension), $valid_extension)) {
            if ($fileSize <= $maxFileSize) {
            } else {
                RequestValidationException::errorMessage('No file was uploaded. File is too large.', 422);
            }
        } else {
            RequestValidationException::errorMessage('Invalid file extension. Supported Formats are csv and xlsx.', 422);
        }
    }
}
