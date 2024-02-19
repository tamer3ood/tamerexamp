<?php

namespace App\Http\Controllers\API\Tamers\Orders;

use App\Http\Controllers\Controller;
use App\Models\TamerOrder;
use Illuminate\Http\Request;
use App\Models\TamerOrderCategoryAttributeItem;
use App\Models\TamerOrderCategoryElement;
use App\Models\Tamer;
use App\Models\TamerCategoryElement;
use App\Models\TamerCategoryService;
use App\Models\TamerOrderCategoryService;
use App\Models\TamerOrderAddOn;
use App\Models\TamerAddOn;
use App\Models\TamerRequirement;
use App\Models\TamerOrderRequirement;
use App\Http\Resources\Tamers\Orders\TamerOrderResource;

class TamerOrderV1Controller extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    public function store(Request $request, $tamer_id)
    {

        $tamer = Tamer::findOrFail($tamer_id);
        if ($tamer->talent_id == auth('api')->id()) {
            return response()->json(['message' => 'can not order your service!']);
        }
        $tamer_category_elements = $tamer->tamerCategoryElements;
        // dd($tamer_category_elements);


        // dd($tamer_category_services);
        $tamer_package_title = "";
        $tamer_package_description = "";
        $tamer_package_price = 0;
        if ($request->input('tamer_order_package_type') == 'basic') {
            $tamer_package_title = $tamer->basic_package_title;
            $tamer_package_description = $tamer->basic_package_description;
            $tamer_package_price = $tamer->basic_package_price;

        } elseif ($request->input('tamer_order_package_type') == 'standard') {
            $tamer_package_title = $tamer->standard_package_title;
            $tamer_package_description = $tamer->standard_package_description;
            $tamer_package_price = $tamer->standard_package_price;
        } else {
            $tamer_package_title = $tamer->advanced_package_title;
            $tamer_package_description = $tamer->advanced_package_description;
            $tamer_package_price = $tamer->advanced_package_price;
        }

        $tamer_order = TamerOrder::create([
            'client_id' => auth('api')->id(),
            'tamer_talent_id' => $tamer->talent_id,
            'tamer_category_id' => $tamer->category_id,
            'tamer_sub_category_id' => $tamer->sub_category_id,
            'tamer_category_type_id' => $tamer->category_type_id,
            'tamer_id' => $tamer->id,
            'tamer_title' => $tamer->title,
            'tamer_description' => $tamer->description,
            'tamer_order_package_type' => $request->input('tamer_order_package_type'),
            'tamer_package_title' => $tamer_package_title,
            'tamer_package_description' => $tamer_package_description,
            'tamer_package_price' => $tamer_package_price,

        ]);

        $category_attribute_items_ids = [];

        foreach ($tamer->tamerCategoryAttributeItems as $cai) {

            $category_attribute_items_ids[] = $cai->pivot->category_attribute_item_id;

        }
        foreach ($category_attribute_items_ids as $category_attribute_item_id) {

            TamerOrderCategoryAttributeItem::create([
                'category_attribute_item_id' => $category_attribute_item_id,
                'tamer_id' => $tamer_id,
                'tamer_order_id' => $tamer_order->id
            ]);
        }


        if ($request->input('tamer_order_package_type') == 'basic') {
            foreach ($tamer_category_elements as $tce) {
                // dd($tamer_category_elements);
                $tamer_order_category_elemets = TamerOrderCategoryElement::create([
                    'tamer_id' => $tamer_id,
                    'tamer_order_id' => $tamer_order->id,
                    'category_element_id' => $tce->category_element_id,
                    'category_element_value' => $tce->basic_pk_element_value,
                ]);
            }
            // $tamer_category_services = $tamer->tamerCategoryServices_basicPk;

            foreach ($tamer->tamerCategoryServices_basicPk as $tcs) {
                //    dd($tcs->category_service_id);
                $tamer_order_category_services = TamerOrderCategoryService::create([
                    'tamer_id' => $tamer_id,
                    'tamer_order_id' => $tamer_order->id,
                    'category_service_id' => $tcs->category_service_id,
                ]);
            }

        } elseif ($request->input('tamer_order_package_type') == 'standard') {
            foreach ($tamer_category_elements as $tce) {
                // dd($tamer_category_elements);
                $tamer_order_category_elemets = TamerOrderCategoryElement::create([
                    'tamer_id' => $tamer_id,
                    'tamer_order_id' => $tamer_order->id,
                    'category_element_id' => $tce->category_element_id,
                    'category_element_value' => $tce->standard_pk_element_value,
                ]);
            }
            foreach ($tamer->tamerCategoryServices_standardPk as $tcs) {
                //    dd($tcs->category_service_id);
                $tamer_order_category_services = TamerOrderCategoryService::create([
                    'tamer_id' => $tamer_id,
                    'tamer_order_id' => $tamer_order->id,
                    'category_service_id' => $tcs->category_service_id,
                ]);
            }

        } elseif ($request->input('tamer_order_package_type') == 'advanced') {
            foreach ($tamer_category_elements as $tce) {
                // dd($tamer_category_elements);
                $tamer_order_category_elemets = TamerOrderCategoryElement::create([
                    'tamer_id' => $tamer_id,
                    'tamer_order_id' => $tamer_order->id,
                    'category_element_id' => $tce->category_element_id,
                    'category_element_value' => $tce->advanced_pk_element_value,
                ]);
            }
            foreach ($tamer->tamerCategoryServices_advancedPk as $tcs) {
                //    dd($tcs->category_service_id);
                $tamer_order_category_services = TamerOrderCategoryService::create([
                    'tamer_id' => $tamer_id,
                    'tamer_order_id' => $tamer_order->id,
                    'category_service_id' => $tcs->category_service_id,
                ]);
            }



        }


        //Add Ons

        $total_add_ons_price = 0;


        // dd($tamer->tamerAddOns);
        if ($request->input('_tamer_oreder_add_on')) {
            foreach ($request->input('_tamer_oreder_add_on') as $key => $toaddon) {
                $addon = TamerAddOn::where('tamer_id', $tamer_id)->where('id', $toaddon['id'])->first();
                // dd($addon !=null);
                if ($addon != null) {
                    $total_add_ons_price = $total_add_ons_price + $addon->extra_price * $toaddon['quantity'];
                    TamerOrderAddOn::create([
                        'tamer_id' => $tamer_id,
                        'tamer_add_on_id' => $toaddon['id'],
                        'tamer_order_id' => $tamer_order->id,
                        'add_on_type' => $addon->add_on_type,
                        'category_eleserv_id' => $addon->category_eleserv_id,
                        'additional_days' => $addon->additional_days,
                        'unit_extra_price' => $addon->extra_price,
                        'quantity' => $toaddon['quantity'],
                        'total_extra_price' => $addon->extra_price * $toaddon['quantity'],
                        'tamer_title_for_custom_add_on' => $addon->tamer_title_for_custom_add_on,
                        'tamer_description_for_custom_add_on' => $addon->tamer_description_for_custom_add_on,
                    ]);
                }

            }

            TamerOrder::findOrFail($tamer_order->id)->update(['total_price' => $total_add_ons_price + $tamer_package_price]);
        }
        // foreach($tamer->tamerAddOns as $tamaaon){
        //     $tamer_order_add_ons = TamerOrderAddOn::create([
        //         'tamer_id'=>$tamer_id,
        //         'tamer_order_id'=>$tamer_order->id,
        //         'add_on_type'=>$request->input('add_on_type'),
        //         'category_eleserv_id'=>$request->input('category_eleserv_id'),
        //         'additional_days'=>$request->input('additional_days'),
        //         'extra_price'=>$request->input('extra_price'),
        //         'tamer_title_for_custom_add_on'=>$request->input('tamer_title_for_custom_add_on'),
        //         'tamer_description_for_custom_add_on'=>$request->input('tamer_description_for_custom_add_on'),
        //     ]);
        // }
        //








    }
    public function checkSelectedPackage($selected_package_type)
    {
        if ($selected_package_type == 'basic') {
            // return 
        }
    }

    public function addOrderRequirements(Request $request, $tamer_id, $tamer_order_id)
    {


        try {
            if ($request->input('_tamer_order_requirements')) {
                foreach ($request->input('_tamer_order_requirements') as $key => $toreq) {
                    $tamer_requirement = TamerRequirement::where('tamer_id', $tamer_id)->where('id', $toreq['tamer_requirement_id'])->first();

                    $tamer_order_requirement = TamerOrderRequirement::create([

                        'tamer_id' => $tamer_id,
                        'tamer_order_id' => $tamer_order_id,
                        'tamer_requirement_id' => $toreq['tamer_requirement_id'],
                        'requirement_text' => $tamer_requirement->requirement_text,
                        'answer_type' => $tamer_requirement->answer_type,
                        'answer_text' => $toreq['answer_text'],
                        'is_allow_more_than_one_answer_for_multi_choice' => $tamer_requirement->is_allow_more_than_one_answer_for_multi_choice,

                        'firstـchoice_text' => $tamer_requirement->firstـchoice_text,
                        'is_firstـchoice_text_selected' => $toreq['is_firstـchoice_text_selected'],

                        'secondـchoice_text' => $tamer_requirement->secondـchoice_text,
                        'is_secondـchoice_text_selected' => $toreq['is_secondـchoice_text_selected'],

                        'thirdـchoice_text' => $tamer_requirement->thirdـchoice_text,
                        'is_thirdـchoice_text_selected' => $toreq['is_thirdـchoice_text_selected'],

                        'fourthـchoice_text' => $tamer_requirement->fourthـchoice_text,
                        'is_fourthـchoice_text_selected' => $toreq['is_fourthـchoice_text_selected'],

                        'fifthـchoice_text' => $tamer_requirement->fifthـchoice_text,
                        'is_fifthـchoice_text_selected' => $toreq['is_fifthـchoice_text_selected'],

                        'sixthـchoice_text' => $tamer_requirement->sixthـchoice_text,
                        'is_sixthـchoice_text_selected' => $toreq['is_sixthـchoice_text_selected'],
                    ]);
                }
            }
        } catch (\Exeption $ex) {

        }



    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    // public function store(Request $request)
    // {
    //     //
    // }

    /**
     * Display the specified resource.
     */
    public function show(TamerOrder $tamerOrder)
    {
        return new TamerOrderResource($tamerOrder);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(TamerOrder $tamerOrder)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, TamerOrder $tamerOrder)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TamerOrder $tamerOrder)
    {
        //
    }
}
