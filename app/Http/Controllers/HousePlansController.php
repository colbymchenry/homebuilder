<?php

namespace App\Http\Controllers;

use App\DesignCategory;
use App\DesignOption;
use App\HousePlan;
use Illuminate\Http\Request;
use App\PriceSheet;
use App\QuickResponse;
use App\Lot;
use App\Project;

class HousePlansController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function indexPlans() {
        return view('house_plans');
    }

    public function indexPlan() {
        $id = \request('id');

        if(!HousePlan::where('id', $id)->exists()) {
            abort(404);
        }

        return view('house_plan')->with('house_plan', HousePlan::where('id', $id)->first());
    }

    public function createHousePlan(Request $request)
    {
        $name = $request['name'];

        if(HousePlan::where('name', $name)->exists()) {
            return QuickResponse::warning('House plan with that name already exists.');
        }

        $house_plan = new HousePlan();
        $house_plan->name = $name;
        $house_plan->save();

        return QuickResponse::success('House plan created.', ['id' => $house_plan->id]);
    }

    public function deleteHousePlan(Request $request)
    {
        $id = $request['id'];

        if(!HousePlan::where('id', $id)->exists()) {
            return QuickResponse::warning('House plan does not exists.');
        }

        $house_plan = HousePlan::where('id', $id)->first();
        $house_plan->delete();

        return QuickResponse::success('House plan deleted.');
    }

    public function createDesignOption(Request $request)
    {
        $name = $request['name'];
        $house_plan = $request['house_plan'];
        $category = $request['category'];

        if(DesignOption::where('name', $name)->where('house_plan', $house_plan)->where('category', $category)->exists()) {
            return QuickResponse::warning('Design option with that name in that house plan already exists.');
        }

        if(!DesignCategory::where('id', $category)->exists()) {
            return QuickResponse::warning('Design category not found.');
        }

        $design_option = new DesignOption();
        $design_option->name = $name;
        $design_option->house_plan = $house_plan;
        $design_option->category = $category;
        $design_option->save();

        return QuickResponse::success('Design option created.', ['id' => $design_option->id]);
    }

    public function deleteDesignOption(Request $request)
    {
        $id = $request['id'];

        if(!DesignOption::where('id', $id)->exists()) {
            return QuickResponse::warning('Design option does not exists.');
        }

        $design_option = DesignOption::where('id', $id)->first();
        $design_option->delete();

        return QuickResponse::success('Design option deleted.');
    }

    public function createPriceSheet(Request $request)
    {
        $name = $request['name'];
        $price = $request['price'];
        $design_option = $request['design_option'];

        if(!DesignOption::where('id', $design_option)->exists()) {
            return QuickResponse::warning('Design option not found.');
        }

        if(PriceSheet::where('name', $name)->where('design_option', $design_option)->exists()) {
            return QuickResponse::warning('Pricing option with that name for that design option already exists.');
        }

        $price_sheet = new PriceSheet();
        $price_sheet->name = $name;
        $price_sheet->price = $price;
        $price_sheet->design_option = $design_option;
        $price_sheet->save();

        return QuickResponse::success('Price sheet created.', ["id" => $price_sheet->id]);
    }

    public function deletePriceSheet(Request $request)
    {
        $id = $request['id'];

        if(!PriceSheet::where('id', $id)->exists()) {
            return QuickResponse::warning('Price sheet does not exists.');
        }

        $price_sheet = PriceSheet::where('id', $id)->first();
        $price_sheet->delete();

        return QuickResponse::success('Price sheet deleted.');
    }

    public function updatePriceSheet(Request $request)
    {
        $id = $request['id'];
        $name = $request['name'];
        $price = $request['price'];

        if(!PriceSheet::where('id', $id)->exists()) {
            return QuickResponse::warning('Price sheet does not exists.');
        }

        $price_sheet = PriceSheet::where('id', $id)->first();
        $price_sheet->name = $name;
        $price_sheet->price = $price;
        $price_sheet->save();

        return QuickResponse::success('Price sheet updated.');
    }

    public function indexBuilder() {
        $id = \request('id');
        $lot = \request('lot');
        $project = \request('project');

        if(!HousePlan::where('id', $id)->exists()) {
            abort(404);
        }

        if(!Lot::where('id', $id)->exists()) {
            abort(404);
        }

        if(!Project::where('id', $id)->exists()) {
            abort(404);
        }


        return view('house_plan_builder')->with('house_plan', HousePlan::where('id', $id)->first())
        ->with('lot', Lot::where('id', $id)->first())->with('project', Project::where('id', $id)->first());
    }

    public function createDesignCategory(Request $request)
    {
        $name = $request['name'];
        $house_plan = $request['house_plan'];

        if(DesignCategory::where('name', $name)->where('house_plan', $house_plan)->exists()) {
            return QuickResponse::warning('Design category with that name in that house plan already exists.');
        }

        $design_category = new DesignCategory();
        $design_category->name = $name;
        $design_category->house_plan = $house_plan;
        $design_category->save();

        return QuickResponse::success('Design category created.', ['id' => $design_category->id]);
    }

    public function deleteDesignCategory(Request $request)
    {
        $id = $request['id'];

        if(!DesignCategory::where('id', $id)->exists()) {
            return QuickResponse::warning('Design category does not exists.');
        }

        $design_category = DesignCategory::where('id', $id)->first();
        $design_category->delete();

        return QuickResponse::success('Design category deleted.');
    }
}
