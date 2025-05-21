<?php

namespace App\Http\Controllers\Api;

use App\Traits\ApiResponser;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Faq;
use Illuminate\Support\Facades\Validator;

class FaqController extends Controller
{
    use ApiResponser;

    public function indexs(Request $request)
    {
        $faqs = Faq::query();
        
        if($request->search){
            $faqs->where('title', 'like', "%{$request->search}%");
        }
        
        $faqs = $faqs->paginate(10);
        
        $data = [
            'faq' => $faqs,
        ];  

        return $this->success($data);
    }

    public function store(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'title'       => ['required', 'string', 'max:255'],
                'description' => ['required'],
            ]
        );
        
        if ($validator->fails()) {
            $messages = $validator->getMessageBag();
            $data     = [];
            return $this->error($data , $messages->first() , 200);
        }

        $post = [
            'title'       => $request->title,
            'description' => $request->description,
        ];

        $faq = Faq::create($post);

        $data = [
            'faq' => $faq
        ];

        return $this->success($data);
    }

    public function update(Request $request)
    {
        $faq = Faq::find($request->id);
            
        if($faq)
        {
            $faq->title = $request->title;
            $faq->description = $request->description;
            
            $faq->save();

            $data = [
                'faq' => $faq
            ];
            return $this->success($data);
        }
        else
        {
            $message = "Faq does not exist";
            return $this->error([] , $message , 200);
        }
    }

    public function destroy(Request $request)
    {
        $faq = Faq::find($request->id);

        $data = [
            'faq'=>[],
        ]; 

        if($faq)
        {
            $faq->delete();
            return $this->success($data);
        }
        else
        {
            $message = "Faq does not exist";
            return $this->error($data , $message , 200);
        }
    }
}