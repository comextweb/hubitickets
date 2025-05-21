<?php

namespace App\Http\Controllers\Api;

use App\Traits\ApiResponser;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Ticket;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    use ApiResponser;

    public function index(Request $request)
    {       
        $categories = Category::select('id','name','parent_id','color')->paginate(10);
    
        $categories = $categories->map(function ($category) use ($categories) {
            $parent = $categories->firstWhere('id', $category['parent_id']);
            $category['parent_name'] = $parent ? $parent['name'] : null;
            return $category;
        });
          // Start Categories Analytics

          $categoriesChart = Ticket::select(
            [
                'tickets.category_id',
                'categories.name',
                'categories.color',
                DB::raw('count(*) as total'),
            ]
        )->join('categories', 'categories.id', '=', 'tickets.category_id')->groupBy('categories.id')->get();

        $total_cat_ticket   = Ticket::count();

        if(count($categoriesChart) > 0)
        {
            foreach($categoriesChart as $category)
            {
            
                $cat_ticket = round((float)(($category->total / 100) * $total_cat_ticket) * 100);

                $chartData[]=[
                    'category' => $category->name,
                    'color' => $category->color,
                    'value' => $cat_ticket,
                ];
            }
        }

        // End Categories Analytics

        $data = [
            'category' =>$categories,
            'category_analytics'=>$chartData,
        ];

        return $this->success($data);
    }

    public function getcategory(Request $request)
    {
        $categories = Category::select('id','name','parent_id','color')->orderBy('id', 'desc')->get();;
    
        $categories = $categories->map(function ($category) use ($categories) {
            $parent = $categories->firstWhere('id', $category['parent_id']);
            $category['parent_name'] = $parent ? $parent['name'] : null;
            return $category;
        });
     
        $data = [
            'category' =>$categories
        ];  

        return $this->success($data);
    }

    public function store(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'name'        => 'required|string|max:255',
                'color'       => 'required|string|max:255',
            ]
        );

        if ($validator->fails()) {
            $messages = $validator->getMessageBag();
            $data     = [];
            return $this->error($data , $messages->first() , 200);
        }

        $post = [
            'name'       => $request->name,
            'color'      => $request->color,
            'parent_id'  => $request->parent_id ?? 0,
            'created_by' => creatorId(),
        ];

        $category = Category::create($post);
        
        $data = [
            'category' =>$category
        ];

        return $this->success($data);             
    }

    public function update(Request $request)
    {   
        $validator = Validator::make(
            $request->all(),
            [
                'name'        => 'required|string|max:255',
                'color'       => 'required|string|max:255',
            ]
        );

        if ($validator->fails()) {
            $messages = $validator->getMessageBag();
            $data     = [];
            return $this->error($data , $messages->first() , 200);
        }

        $category            = Category::find($request['id']);        

        if($category)
        {
            $category->name      = $request->name;
            $category->color     = $request->color;
            $category->parent_id = $request->parent_id ?? 0;
            $category->save();
                        
            $data = [
                'category' => $category
            ];
            return $this->success($data);
        }
        else{
            $message = "Category does not exist";
            return $this->error([] , $message , 200);
        }  
    }

    public function destroy(Request $request)
    {
        $category = Category::find($request->id);

        $data = [
            'category'=>[],
        ]; 

        if($category)
        {            
            $category->delete();
            return $this->success($data);
        }
        else{
            $message = "Category does not exist";
            return $this->error($data , $message , 200);
        }
    }
}