<?php

namespace App\Http\Controllers;

use App\Http\Requests\AdvertisementStoreRequest;
use App\Models\Advertisement;
use App\Models\Business;
use App\Models\IranCity;
use App\Models\IranProvince;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\File;

class AdvertiserController extends Controller
{
    public function Panel(Request $request)
    {
        $advertisements = Advertisement::where('user_id', Auth::id());
        $advertisements = ($request->has('sort')) ? $advertisements->orderBy($request->get('sort'), 'DESC')->get() : $advertisements->latest()->get();
        return view('advertiser.panel.index', compact(['advertisements']));
    }

    public function AddAdvertise()
    {
        return view('advertiser.panel.addAvertise');
    }

//    public function SubmitAdvertise(Request $request)
    public function SubmitAdvertise(AdvertisementStoreRequest $request)
    {
        // incoming request validation will handles inside AdertisementStoreRequest class.
        $advertisement = new Advertisement();
        $advertisement->title = $request['business_name'];
        $advertisement->desc = '';
        $advertisement->confirmed = 0;

        // @todo solve unloggedin users, or think for a new way to handle this issue
        $advertisement->user_id = (Auth::check()) ? Auth::id() : 1;

        $advertisement->fullname = (Auth::check()) ? Auth::user()->name : $request['fullname'];
        $advertisement->phone = (Auth::check()) ? Auth::user()->phone_number : $request['phone'];
        $advertisement->business_name = $request['business_name'];
        $advertisement->business_categories = $request['business_category'];
        $advertisement->work_hours = $request['work_hours'];
        $advertisement->off_days = $request['off_days'];
        $advertisement->address = $request['address'];
        $advertisement->business_number = $request['business_number'];
        $advertisement->social_media = json_encode([
            'instagram' => $request['instagram'],
            'telegram' => $request['telegram'],
            'whatsapp' => $request['whatsapp'],
            'eitaa' => $request['eitaa'],
            'other_socials' => json_decode($request['other_socials']),
        ]);

        // @todo: create a Media model to handle user uploads better than any time!
        if ($request->hasFile('business_images')) {
            $business_images_backpack = [];
            $loop = 0;
            foreach ($request->file('business_images') as $image) {
                $loop ++;
                $hashName = $image->hashName();
                $extension = $image->extension();
                $defaultName = time().'-'.$hashName;
                $seoName = "$loop-" . str_replace(' ', '-', $request['business_name']) . ".$extension";

                $path = $image->storeAs('uploads/'.Auth::id(), $seoName, 'public');
                $business_images_backpack[] = $path;
            }
            $advertisement->business_images = json_encode($business_images_backpack);
        }

        $advertisement->province = IranProvince::find($request['province'])->name;
        $advertisement->iran_province_id = $request['city'];
        $advertisement->city = IranCity::find($request['city'])->name;
        $advertisement->iran_city_id = $request['city'];
        $advertisement->latitude = $request['lat'];
        $advertisement->longitude = $request['lng'];
        $advertisement->published_at = now();
        $advertisement->save();


        return response()->json([
            'status' => 200,
            'allowed' => true,
            'timestamp' => time(),
            'messages' => [
                'fa' => 'با موفقیت اضافه شد.',
                'en' => 'advertisement successfully submitted.',
            ],
        ]);

        return redirect()->back()->with(['message' => 'با موفقیت ثبت شد.']);
    }

    public function ListCities(Request $request)
    {
        $province = IranProvince::find($request->get('province'));
            if (is_null($province)) {
            return response()->json([
                'status' => 400,
                'timestamp' => time(),
                'allowed' => false,
                'errors' => [
                    'fa' => 'استان یافت نشد.',
                    'en' => 'Province not found.'
                ],
            ]);
        } else {
            $output = '';
            foreach ($province->cities as $city) {
                $output .= "<option value={$city->id}>{$city->name}</option>";
            }

            return response()->json([
                'status' => 200,
                'timestamp' => time(),
                'allowed' => true,
                'province' => $province,
                'html' => $output,
            ]);
        }
    }
}
