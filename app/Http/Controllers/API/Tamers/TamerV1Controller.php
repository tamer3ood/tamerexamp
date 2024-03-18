<?php

namespace App\Http\Controllers\API\Tamers;

use Exception;
use App\Models\Tamer;
use App\Models\Category;
use App\Models\TamerFile;
use App\Models\TamerStep;
use App\Models\TamerAddOn;
use Illuminate\Http\Request;
use App\Models\TamerQuestion;
use App\Models\TamerRequirement;
use App\Models\CategoryAttribute;

use App\Http\Controllers\Controller;
use App\Models\TamerCategoryElement;
use App\Models\TamerCategoryService;
use App\Models\CategoryAttributeItem;

use App\Http\Resources\Tamers\Tamer1Resource;
use App\Http\Resources\Tamers\Tamer2Resource;
use App\Http\Resources\Tamers\Tamer3Resource;
use App\Http\Resources\Tamers\Tamer4Resource;
use App\Http\Resources\Tamers\Tamer5Resource;
use App\Http\Resources\Tamers\Tamer6Resource;
use App\Http\Resources\Tamers\TamerResource;

class TamerV1Controller extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // return TamerCategoryElement::with('categoryElement')->get();

        return TamerResource::collection(Tamer::all());
    }
    public function store(Request $request)
    {
        $request->validate([
            "sub_category_id" => "required|exists:categories,id",
            "category_id" => "required|exists:categories,id",
            "title" => "required|string",
            "tamer_category_attribute_items" => "array",
            "_categoryElement_package.*.category_element_id" => "required|exists:category_elements,id",
            "is_basic" => "accepted",
            "_categoryElement_package.*.basic_pk_element_value" => "required",
            "_categoryElement_package.*.standard_pk_element_value" => "required_if:is_standard,1",
            "_categoryElement_package.*.advanced_pk_element_value" => "required_if:is_advanced,1",
            "basic_package_price" => "required",
            "standard_package_price" => "required_if:is_standard,1",
            "advanced_package_price" => "required_if:is_advanced,1",
            "privacy_notice_agreement" => "accepted",
            "term_of_service_agreement" => "accepted",
            "max_no_of_simultaneous_tamers" => "required|numeric|max:3",
            // "tamer_search_tags" => "exists:search_tags,id|array",
        ]);
        try {


            $category = Category::findOrFail($request->input("category_id"));
            $sub_category = Category::findOrFail($request->input("sub_category_id"));
            // dd($sub_category);

            $validationMessages = [];
            $validItems = [];

            // $sub_categories = $category->subCategories;




            $sub_category_ids = $category->subCategories->pluck("id")->all();
            if (count($sub_category_ids) > 0) {
                if (!in_array($request->input('sub_category_id'), $sub_category_ids)) {
                    $validationMessages[] = "Selected Sub Category not belongs to the main category";

                }
            }



            $attributes = $sub_category->categoryAttributes;
            // dd($attributes);
            foreach ($attributes as $key => $attribute) {
                // if ($attribute->is_required == 1) {
                $attributeItems = $attribute->categoryAttributeItems->pluck("id")->all();
                $submittedItems = $request->input("tamer_category_attribute_items", []);
                $intersect = array_intersect($submittedItems, $attributeItems);
                $numItems = count($intersect);

                if ($numItems <= 0 && $attribute->is_required) {
                    $validationMessages[] = 'The ' . $attribute->title_en . ' field is required, ';
                }
                if (

                    // $numItems < $attribute->min_no_of_items ||
                    $numItems > $attribute->max_no_of_items
                    // $numItems < 1 ||
                    // $numItems > $attribute->max_no_of_items
                ) {
                    $validationMessages[] = 'The ' . $attribute->title_en . ' field must have a maximum ' .
                        $attribute->max_no_of_items . ' at index: ' . $key;
                }


                $validItems = array_merge($validItems, $intersect);
                $submittedItems = CategoryAttributeItem::whereIn(
                    "id",
                    $request->input("tamer_category_attribute_items", [])
                )->get();
                foreach ($submittedItems as $submittedItem) {
                    if (
                        $submittedItem->categoryAttribute->category_id !==
                        $sub_category->id
                    ) {
                        return response()->json(
                            [
                                "error" =>
                                    "The submitted item does not belong to the same category as the service.",
                            ],
                            422
                        );
                    }
                }

                if (
                    empty ($validItems) &&
                    count($validationMessages) === 0
                ) {
                    return response()->json(
                        [
                            "error" =>
                                "The submitted items do not belong to any of the category attributes.",
                        ],
                        422
                    );
                }

                if (!empty ($validationMessages)) {
                    return response()->json(
                        ["errors" => $validationMessages],
                        422
                    );
                }
                // }
            }


            if (!empty ($validationMessages)) {
                return response()->json(
                    ["errors" => $validationMessages],
                    422
                );
            }

            //تم وضع الرقم ١٠٠ مكان تالنت 
            $tamerData = [
                "title" => $request->input("title"),
                "talent_id" => auth('api')->id(),
                "category_id" => $request->input("category_id"),
                "sub_category_id" => $request->input("sub_category_id"),
                "category_type_id" => $request->input("category_type_id"),
                "has_basic_package" => $request->input("is_basic"),
                "has_standard_package" => $request->input("is_standard"),
                "has_advanced_package" => $request->input("is_advanced"),
                "standard_package_price" => $request->input("standard_package_price"),
                "advanced_package_price" => $request->input("advanced_package_price"),
                "max_no_of_simultaneous_tamers" => $request->input("max_no_of_simultaneous_tamers"),
                "term_of_service_agreement" => $request->input("term_of_service_agreement"),
                "privacy_notice_agreement" => $request->input("privacy_notice_agreement"),


            ];

            $tamer = Tamer::create($tamerData);
            // if($tamer){
            $tamer->tamerCategoryAttributeItems()->sync($validItems);

            if ($request->tamer_search_tags) {
                $tamer_search_tags = $request->input("tamer_search_tags", []);
                $tamer->tamerSearchTags()->sync($tamer_search_tags);
            }

            $selected_category_ids = [];

            if ($request->input("_categoryElement_package")) {
                foreach ($request->input("_categoryElement_package") as $key => $pk) {


                    $selected_category_ids[] = $pk["category_element_id"];
                    if ($request->input("is_standard") == 0) {
                        $pk["standard_pk_element_value"] = 0;
                    }

                    if ($request->input("is_advanced") == 1) {

                        if ($request->input("is_standard") == 0) {
                            return response()->json(['message' => 'fill standard first'], 422);
                            // return;
                        }

                    } else {
                        $pk["advanced_pk_element_value"] = 0;
                    }
                    //




                    // dd( $pk["advanced_pk_element_value"]);


                    $tamer_category_element = TamerCategoryElement::create([
                        "tamer_id" => $tamer->id,
                        // 'package_type'=>$pk['package_type'],
                        "category_element_id" => $pk["category_element_id"],
                        "basic_pk_element_value" =>
                            $pk["basic_pk_element_value"],
                        "standard_pk_element_value" =>
                            $pk["standard_pk_element_value"],
                        "advanced_pk_element_value" =>
                            $pk["advanced_pk_element_value"],
                    ]);
                }
            }

            if ($request->input("_categoryService_package")) {
                foreach ($request->input("_categoryService_package") as $key => $pk) {
                    $tamer_category_service = TamerCategoryService::create([
                        "tamer_id" => $tamer->id,
                        "is_basic_pk" => $pk["is_basic_pk"],
                        "is_standard_pk" => $pk["is_standard_pk"],
                        "is_advanced_pk" => $pk["is_advanced_pk"],
                        "category_service_id" => $pk["category_service_id"],
                    ]);
                }
            }

            if ($request->input("_tamer_add_ons")) {
                foreach ($request->input("_tamer_add_ons") as $key => $pk) {

                    if ($pk["add_on_type"] == "customAddOn") {


                        if (!$pk["title_for_custom_add_on"]) {
                            $validationMessages[] =

                                "The Title For Custom Add On is Required" .

                                " at index: " .
                                $key;
                        }
                        if (!$pk["description_for_custom_add_on"]) {
                            $validationMessages[] =

                                "The Description For Custom Add On is Required" .

                                " at index: " .
                                $key;
                        }

                    }
                    if ($pk["add_on_type"] == "categoryService") {
                        if (!$pk["category_eleserv_id"]) {
                            $validationMessages[] =

                                "The Category Service ID is Required" .

                                " at index: " .
                                $key;
                        }
                        // $request->validate([
                        //     "_tamer_add_ons.*.category_eleserv_id" => "required_if:".$pk["add_on_type"].",==,categoryService",

                        // ]);
                    }
                    if ($pk["add_on_type"] == "categoryElement") {
                        if (!$pk["category_eleserv_id"]) {
                            $validationMessages[] =

                                "The Category Element ID is Required" .

                                " at index: " .
                                $key;
                        }

                    }


                    if (!empty ($validationMessages)) {
                        return response()->json(
                            ["errors" => $validationMessages],
                            422
                        );
                    }
                    $tamer_add_on = TamerAddOn::create([
                        "tamer_id" => $tamer->id,
                        "add_on_type" => $pk["add_on_type"],
                        "category_eleserv_id" => $pk["category_eleserv_id"],
                        "additional_days" => $pk["additional_days"],
                        "extra_price" => $pk["extra_price"],
                        "title_for_custom_add_on" =>
                            $pk["title_for_custom_add_on"],
                        "description_for_custom_add_on" =>
                            $pk["description_for_custom_add_on"],
                    ]);



                }
            }

            if (count($request->_files) > 0) {
                foreach ($request->_files as $key => $mydata) {
                    // dd($request->_files);
                    if ($mydata["file_url"]) {
                        $filename =
                            time() .
                            "-" .
                            \Str::random(15) .
                            "-" .
                            $mydata["file_url"]->getClientOriginalName();
                        // $filename = time().'-'. \Str::random(15);
                        $mydata["file_url"]->storeAs(
                            "tamer_files/",
                            $filename,
                            "s3"
                        );
                        $tamer = TamerFile::create([
                            "tamer_id" => $request->input("tamer_id"),
                            "file_type" => $mydata["file_type"],
                            "file_url" => $filename,
                        ]);
                    }
                }
            }
            $selected_multiple_choice_no = 0;
            foreach ($request->input("_tamer_requirement") as $key => $req) {
                $this->validate($request, [
                    "_tamer_requirement.*.requirement_text" => "required",
                    "_tamer_requirement.*.answer_type" => "required",
                    "_tamer_requirement.*.is_mandatory_requirement" => "required",
                ]);

                if ($req["answer_type"] == 'multipleChoice') {
                    if (!$req['firstـchoice_text'] || !$req['secondـchoice_text']) {
                        $validationMessages[] =

                            "you have to chose two at least" .

                            " at index: " .
                            $key;
                    }
                    // if()

                }
                // if ($req["index"] != null) {
                //     $this->validate($request, [
                //         "_tamer_requirement.*.requirement_text" => "required",
                //         "_tamer_requirement.*.answer_type" => "required",
                //     ]);
                // }
                // dd('ff');
                if (!empty ($validationMessages)) {
                    return response()->json(
                        ["errors" => $validationMessages],
                        422
                    );
                }
                $tamer_req = TamerRequirement::create([
                    "tamer_id" => $tamer->id,
                    "requirement_text" => $req["requirement_text"],
                    "answer_type" => $req["answer_type"],
                    "is_mandatory_requirement" =>
                        $req["is_mandatory_requirement"],
                    "is_allow_more_than_one_answer_for_multi_choice" =>
                        $req["is_allow_more_than_one_answer_for_multi_choice"],
                    "firstـchoice_text" =>
                        $req["firstـchoice_text"],
                    "secondـchoice_text" =>
                        $req["secondـchoice_text"],
                    "thirdـchoice_text" =>
                        $req["thirdـchoice_text"],
                    "fourthـchoice_text" =>
                        $req["fourthـchoice_text"],
                    "fifthـchoice_text" =>
                        $req["fifthـchoice_text"],
                    "sixthـchoice_text" =>
                        $req["sixthـchoice_text"],


                ]);


            }
            if ($request->input("_tamer_step")) {
                foreach ($request->input("_tamer_step") as $key => $req) {
                    // if ($req["index"] != null) {
                    // dd($req['index']);
                    $this->validate($request, [
                        "_tamer_step.*.name" => "required|string",
                        "_tamer_step.*.description" => "required|string",
                    ]);
                    // }
                    $tamer = TamerStep::create([
                        "tamer_id" => $request->input("tamer_id"),
                        "name" => $req["name"],
                        "description" => $req["description"],
                    ]);
                }
            }

            if ($request->input("_tamer_question")) {
                foreach ($request->input("_tamer_question") as $key => $req) {
                    // if ($req["index"] != null) {
                    // dd($req['index']);
                    $this->validate($request, [
                        "_tamer_question.*.question_text" =>
                            "required|string",
                        "_tamer_question.*.answer_text" =>
                            "required|string",
                    ]);
                    // }
                    $tamer = TamerQuestion::create([
                        "tamer_id" => $tamer->id,
                        "question_text" => $req["question_text"],
                        "answer_text" => $req["answer_text"],
                    ]);
                }
            }

            return response()->json([
                'message' => 'saved Successfully!',
                'tamer_id' => $tamer->id,
            ], 200);

            // }


        } catch (Exception $e) {
            return $e->getMessage();
            // return response()->json(
            //     [
            //         "error" => "Somethings Errors",

            //     ],
            //     422
            // );
        }

    }

    public function store_stp1(Request $request)
    {
        $request->validate([
            "sub_category_id" => "required|exists:categories,id",
            "category_id" => "required|exists:categories,id",
            "title" => "required|string",
            "tamer_category_attribute_items" => "array",
            // "tamer_search_tags" => "exists:search_tags,id|array",
        ]);
        try {


            $category = Category::findOrFail($request->input("category_id"));
            $sub_category = Category::findOrFail($request->input("sub_category_id"));
            // dd($sub_category);

            $validationMessages = [];
            $validItems = [];

            // $sub_categories = $category->subCategories;




            $sub_category_ids = $category->subCategories->pluck("id")->all();
            if (count($sub_category_ids) > 0) {
                if (!in_array($request->input('sub_category_id'), $sub_category_ids)) {
                    $validationMessages[] = "Selected Sub Category not belongs to the main category";

                }
            }



            $attributes = $sub_category->categoryAttributes;
            // dd($attributes);
            foreach ($attributes as $key => $attribute) {
                // if ($attribute->is_required == 1) {
                $attributeItems = $attribute->categoryAttributeItems->pluck("id")->all();
                $submittedItems = $request->input("tamer_category_attribute_items", []);
                $intersect = array_intersect($submittedItems, $attributeItems);
                $numItems = count($intersect);

                if ($numItems <= 0 && $attribute->is_required) {
                    $validationMessages[] = 'The ' . $attribute->title_en . ' field is required, ';
                }
                // if (

                //     // $numItems < $attribute->min_no_of_items ||
                //     $numItems > $attribute->max_no_of_items
                //     // $numItems < 1 ||
                //     // $numItems > $attribute->max_no_of_items
                // ) {
                //     $validationMessages[] = 'The ' . $attribute->title_en . ' field must have a maximum of ' .
                //         $attribute->max_no_of_items . ' at index: ' . $key;
                // }

                $validItems = array_merge($validItems, $intersect);
                $submittedItems = CategoryAttributeItem::whereIn(
                    "id",
                    $request->input("tamer_category_attribute_items", [])
                )->get();
                foreach ($submittedItems as $submittedItem) {
                    if (
                        $submittedItem->categoryAttribute->category_id !==
                        $sub_category->id
                    ) {
                        return response()->json(
                            [
                                "error" =>
                                    "The submitted item does not belong to the same category as the service.",
                            ],
                            422
                        );
                    }
                }

                // if (
                //     empty($validItems) &&
                //     count($validationMessages) === 0
                // ) {
                //     return response()->json(
                //         [
                //             "error" =>
                //                 "The submitted items do not belong to any of the category attributes.",
                //         ],
                //         422
                //     );
                // }

                // if (!empty($validationMessages)) {
                //     return response()->json(
                //         ["errors" => $validationMessages],
                //         422
                //     );
                // }
                // }
            }


            if (!empty ($validationMessages)) {
                return response()->json(
                    ["errors" => $validationMessages],
                    422
                );
            }
            //تم وضع الرقم ١٠٠ مكان تالنت 
            $tamerData = [
                "title" => $request->input("title"),
                "talent_id" => auth('api')->id(),
                "category_id" => $request->input("category_id"),
                "sub_category_id" => $request->input("sub_category_id"),
                "category_type_id" => $request->input("category_type_id"),
            ];

            $tamer = Tamer::create($tamerData);
            // if($tamer){
            $tamer->tamerCategoryAttributeItems()->sync($validItems);

            if ($request->tamer_search_tags) {
                $tamer_search_tags = $request->input("tamer_search_tags", []);
                $tamer->tamerSearchTags()->sync($tamer_search_tags);
            }
            return response()->json([
                'message' => 'Step no 1 saved Successfully!',
                'tamer_id' => $tamer->id,
            ], 200);

            // }


        } catch (Exception $e) {
            return $e->getMessage();
            // return response()->json(
            //     [
            //         "error" => "Somethings Errors",

            //     ],
            //     422
            // );
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store_stp2(Request $request)
    {
        $request->validate([
            "tamer_id" => "required|exists:tamers,id",
            "_categoryElement_package.*.category_element_id" => "required|exists:category_elements,id",
            "is_basic" => "accepted",
            "_categoryElement_package.*.basic_pk_element_value" => "required",
            "_categoryElement_package.*.standard_pk_element_value" => "required_if:is_standard,1",
            "_categoryElement_package.*.advanced_pk_element_value" => "required_if:is_advanced,1",
            "basic_package_price" => "required",
            "standard_package_price" => "required_if:is_standard,1",
            "advanced_package_price" => "required_if:is_advanced,1",
        ]);
        try {
            $validationMessages = [];
            $selected_category_ids = [];

            if ($request->input("_categoryElement_package")) {
                foreach ($request->input("_categoryElement_package") as $key => $pk) {


                    $selected_category_ids[] = $pk["category_element_id"];
                    if ($request->input("is_standard") == 0) {
                        $pk["standard_pk_element_value"] = 0;
                    }

                    if ($request->input("is_advanced") == 1) {

                        if ($request->input("is_standard") == 0) {
                            return response()->json(['message' => 'fill standard first'], 422);
                            // return;
                        }

                    } else {
                        $pk["advanced_pk_element_value"] = 0;
                    }
                    //




                    // dd( $pk["advanced_pk_element_value"]);


                    $tamer_category_element = TamerCategoryElement::create([
                        "tamer_id" => $request->input("tamer_id"),
                        // 'package_type'=>$pk['package_type'],
                        "category_element_id" => $pk["category_element_id"],
                        "basic_pk_element_value" =>
                            $pk["basic_pk_element_value"],
                        "standard_pk_element_value" =>
                            $pk["standard_pk_element_value"],
                        "advanced_pk_element_value" =>
                            $pk["advanced_pk_element_value"],
                    ]);
                }
            }

            if ($request->input("_categoryService_package")) {
                foreach ($request->input("_categoryService_package") as $key => $pk) {
                    $tamer_category_service = TamerCategoryService::create([
                        "tamer_id" => $request->input("tamer_id"),
                        "is_basic_pk" => $pk["is_basic_pk"],
                        "is_standard_pk" => $pk["is_standard_pk"],
                        "is_advanced_pk" => $pk["is_advanced_pk"],
                        "category_service_id" => $pk["category_service_id"],
                    ]);
                }
            }

            if ($request->input("_tamer_add_ons")) {
                foreach ($request->input("_tamer_add_ons") as $key => $pk) {

                    if ($pk["add_on_type"] == "customAddOn") {


                        if (!$pk["title_for_custom_add_on"]) {
                            $validationMessages[] =

                                "The Title For Custom Add On is Required" .

                                " at index: " .
                                $key;
                        }
                        if (!$pk["description_for_custom_add_on"]) {
                            $validationMessages[] =

                                "The Description For Custom Add On is Required" .

                                " at index: " .
                                $key;
                        }

                    }
                    if ($pk["add_on_type"] == "categoryService") {
                        if (!$pk["category_eleserv_id"]) {
                            $validationMessages[] =

                                "The Category Service ID is Required" .

                                " at index: " .
                                $key;
                        }
                        // $request->validate([
                        //     "_tamer_add_ons.*.category_eleserv_id" => "required_if:".$pk["add_on_type"].",==,categoryService",

                        // ]);
                    }
                    if ($pk["add_on_type"] == "categoryElement") {
                        if (!$pk["category_eleserv_id"]) {
                            $validationMessages[] =

                                "The Category Element ID is Required" .

                                " at index: " .
                                $key;
                        }

                    }


                    if (!empty ($validationMessages)) {
                        return response()->json(
                            ["errors" => $validationMessages],
                            422
                        );
                    }
                    $tamer_add_on = TamerAddOn::create([
                        "tamer_id" => $request->input("tamer_id"),
                        "add_on_type" => $pk["add_on_type"],
                        "category_eleserv_id" => $pk["category_eleserv_id"],
                        "additional_days" => $pk["additional_days"],
                        "extra_price" => $pk["extra_price"],
                        "title_for_custom_add_on" =>
                            $pk["title_for_custom_add_on"],
                        "description_for_custom_add_on" =>
                            $pk["description_for_custom_add_on"],
                    ]);



                }
            }
            $tamer = Tamer::findOrFail($request->input('tamer_id'));
            $tamer->has_basic_package = $request->input('is_basic');
            $tamer->has_standard_package = $request->input('is_standard');
            $tamer->has_advanced_package = $request->input('is_advanced');
            $tamer->basic_package_price = $request->input('basic_package_price');
            $tamer->standard_package_price = $request->input('standard_package_price');
            $tamer->advanced_package_price = $request->input('advanced_package_price');
            $tamer->update();
        } catch (Exception $ex) {
            return response()->json(
                [
                    // "error" => "Somethings Errors",
                    'errors' => $ex->getMessage(),
                ],
                422
            );
        }
    }
    public function store_stp3(Request $request)
    {
        $this->validate($request, [
            "tamer_id" => "required|exists:tamers,id",
        ]);
        try {
            if (count($request->_files) > 0) {
                foreach ($request->_files as $key => $mydata) {
                    // dd($request->_files);
                    if ($mydata["file_url"]) {
                        $filename =
                            time() .
                            "-" .
                            \Str::random(15) .
                            "-" .
                            $mydata["file_url"]->getClientOriginalName();
                        // $filename = time().'-'. \Str::random(15);
                        $mydata["file_url"]->storeAs(
                            "tamer_files/",
                            $filename,
                            "s3"
                        );
                        $tamer = TamerFile::create([
                            "tamer_id" => $request->input("tamer_id"),
                            "file_type" => $mydata["file_type"],
                            "file_url" => $filename,
                        ]);
                    }
                }
            }
        } catch (Exception $ex) {
            return response()->json(
                [
                    "error" => $ex->getMessage(),
                ],
                422
            );
        }
    }
    public function store_stp4(Request $request)
    {
        $this->validate($request, [
            "tamer_id" => "required|exists:tamers,id",
        ]);

        try {
            $validationMessages = [];
            $selected_multiple_choice_no = 0;
            foreach ($request->input("_tamer_requirement") as $key => $req) {
                $this->validate($request, [
                    "_tamer_requirement.*.requirement_text" => "required",
                    "_tamer_requirement.*.answer_type" => "required",
                    "_tamer_requirement.*.is_mandatory_requirement" => "required",
                ]);

                if ($req["answer_type"] == 'multipleChoice') {
                    if (!$req['firstـchoice_text'] || !$req['secondـchoice_text']) {
                        $validationMessages[] =

                            "you have to chose two at least" .

                            " at index: " .
                            $key;
                    }
                    // if()

                }
                // if ($req["index"] != null) {
                //     $this->validate($request, [
                //         "_tamer_requirement.*.requirement_text" => "required",
                //         "_tamer_requirement.*.answer_type" => "required",
                //     ]);
                // }
                // dd('ff');
                if (!empty ($validationMessages)) {
                    return response()->json(
                        ["errors" => $validationMessages],
                        422
                    );
                }
                $tamer_req = TamerRequirement::create([
                    "tamer_id" => $request->input("tamer_id"),
                    "requirement_text" => $req["requirement_text"],
                    "answer_type" => $req["answer_type"],
                    "is_mandatory_requirement" =>
                        $req["is_mandatory_requirement"],
                    "is_allow_more_than_one_answer_for_multi_choice" =>
                        $req["is_allow_more_than_one_answer_for_multi_choice"],
                    "firstـchoice_text" =>
                        $req["firstـchoice_text"],
                    "secondـchoice_text" =>
                        $req["secondـchoice_text"],
                    "thirdـchoice_text" =>
                        $req["thirdـchoice_text"],
                    "fourthـchoice_text" =>
                        $req["fourthـchoice_text"],
                    "fifthـchoice_text" =>
                        $req["fifthـchoice_text"],
                    "sixthـchoice_text" =>
                        $req["sixthـchoice_text"],


                ]);


            }
        } catch (Exception $ex) {
            return response()->json(
                [
                    "error" => "Somethings Errors",
                ],
                422
            );
        }
    }
    public function store_stp5(Request $request)
    {
        $this->validate($request, [
            "tamer_id" => "required|exists:tamers,id",
            "description" => "required|string",
        ]);
        try {
            if ($request->input("_tamer_step")) {
                foreach ($request->input("_tamer_step") as $key => $req) {
                    // if ($req["index"] != null) {
                    // dd($req['index']);
                    $this->validate($request, [
                        "_tamer_step.*.name" => "required|string",
                        "_tamer_step.*.description" => "required|string",
                    ]);
                    // }
                    $tamer = TamerStep::create([
                        "tamer_id" => $request->input("tamer_id"),
                        "name" => $req["name"],
                        "description" => $req["description"],
                    ]);
                }
            }

            if ($request->input("_tamer_question")) {
                foreach ($request->input("_tamer_question") as $key => $req) {
                    // if ($req["index"] != null) {
                    // dd($req['index']);
                    $this->validate($request, [
                        "_tamer_question.*.question_text" =>
                            "required|string",
                        "_tamer_question.*.answer_text" =>
                            "required|string",
                    ]);
                    // }
                    $tamer = TamerQuestion::create([
                        "tamer_id" => $request->input("tamer_id"),
                        "question_text" => $req["question_text"],
                        "answer_text" => $req["answer_text"],
                    ]);
                }
            }
            $tamer = Tamer::findOrFail($request->input("tamer_id"));

            if ($tamer) {
                $tamer->description = $request->input("description");
            }
            $tamer->update();
        } catch (Exception $ex) {
            return response()->json(
                [
                    "error" => "Somethings Errors",
                ],
                422
            );
        }
    }

    public function store_stp6(Request $request)
    {
        $this->validate($request, [
            "tamer_id" => "required|exists:tamers,id",
            "privacy_notice_agreement" => "accepted",
            "term_of_service_agreement" => "accepted",
            "max_no_of_simultaneous_tamers" => "required|numeric|max:3",
        ]);
        try {


            // Finalize - review
            $tamer = Tamer::findOrFail($request->input("tamer_id"));

            if ($tamer) {
                $tamer->max_no_of_simultaneous_tamers = $request->input(
                    "max_no_of_simultaneous_tamers"
                );
                $tamer->term_of_service_agreement = $request->input(
                    "term_of_service_agreement"
                );
                $tamer->privacy_notice_agreement = $request->input(
                    "privacy_notice_agreement"
                );
                $tamer->status = "underReview";
            }
            $tamer->update();
        } catch (Exception $ex) {

            return response()->json(
                [
                    // "error" => $ex->getMessage(),
                    "error" => "Somethings Errors",
                ],
                422
            );
        }
    }

    public function show(Tamer $tamer)
    {
        return new TamerResource($tamer);
    }

    /**
     * Display the specified resource.
     */
    public function show_shw1(Tamer $tamer)
    {
        return new Tamer1Resource($tamer);
    }

    public function show_shw2(Tamer $tamer)
    {
        return new Tamer2Resource($tamer);
    }

    public function show_shw3(Tamer $tamer)
    {
        return new Tamer3Resource($tamer);
    }

    public function show_shw4(Tamer $tamer)
    {
        try {
            return new Tamer4Resource($tamer);
        } catch (Exception $e) {
            return $e->getMessage();
            // return response()->json(
            //     [
            //         "error" => "Somethings Errors",

            //     ],
            //     422
            // );
        }

    }

    public function show_shw5(Tamer $tamer)
    {
        return new Tamer5Resource($tamer);
    }

    public function show_shw6(Tamer $tamer)
    {

        return new Tamer6Resource($tamer);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Tamer $tamer)
    {
        //
    }


    //update

    public function update_stp1(Request $request, Tamer $tamer)
    {
        $request->validate([
            "sub_category_id" => "required|exists:categories,id",
            "category_id" => "required|exists:categories,id",
            "title" => "required|string",
            "tamer_category_attribute_items" => "array",
            // "tamer_search_tags" => "exists:search_tags,id|array",
        ]);
        try {


            $category = Category::findOrFail($request->input("category_id"));
            $sub_category = Category::findOrFail($request->input("sub_category_id"));
            // dd($sub_category);

            $validationMessages = [];
            $validItems = [];

            // $sub_categories = $category->subCategories;




            $sub_category_ids = $category->subCategories
                ->pluck("id")
                ->all();
            if (count($sub_category_ids) > 0) {
                if (!in_array($request->input('sub_category_id'), $sub_category_ids)) {
                    $validationMessages[] =
                        "Selected Sub Category not belongs to the main category";

                }
            }



            $attributes = $sub_category->categoryAttributes;
            // dd($attributes);
            foreach ($attributes as $key => $attribute) {
                if ($attribute->is_required == 1) {
                    $attributeItems = $attribute->categoryAttributeItems
                        ->pluck("id")
                        ->all();


                    $submittedItems = $request->input(
                        "tamer_category_attribute_items",
                        []
                    );
                    $intersect = array_intersect(
                        $submittedItems,
                        $attributeItems
                    );

                    $numItems = count($intersect);
                    // if (
                    //     $numItems < 1 ||
                    //     $numItems > $attribute->max_no_of_items
                    // ) {
                    //     $validationMessages[] =
                    //         "The " .
                    //         $attribute->title_en .
                    //         " field must have a maximum of " .
                    //         $attribute->max_no_of_items .
                    //         " at index: " .
                    //         $key;
                    // }

                    $validItems = array_merge($validItems, $intersect);
                    $submittedItems = CategoryAttributeItem::whereIn(
                        "id",
                        $request->input("tamer_category_attribute_items", [])
                    )->get();
                    foreach ($submittedItems as $submittedItem) {
                        if (
                            $submittedItem->categoryAttribute->category_id !==
                            $sub_category->id
                        ) {
                            return response()->json(
                                [
                                    "error" =>
                                        "The submitted item does not belong to the same category as the service.",
                                ],
                                422
                            );
                        }
                    }

                    if (
                        empty ($validItems) &&
                        count($validationMessages) === 0
                    ) {
                        return response()->json(
                            [
                                "error" =>
                                    "The submitted items do not belong to any of the category attributes.",
                            ],
                            422
                        );
                    }

                    // if (!empty($validationMessages)) {
                    //     return response()->json(
                    //         ["errors" => $validationMessages],
                    //         422
                    //     );
                    // }
                }
            }


            if (!empty ($validationMessages)) {
                return response()->json(
                    ["errors" => $validationMessages],
                    422
                );
            }
            $tamerData = [
                "title" => $request->input("title"),
                "talent_id" => auth('api')->id(),
                "category_id" => $request->input("category_id"),
                "sub_category_id" => $request->input("sub_category_id"),
                "category_type_id" => $request->input("category_type_id"),
            ];

            $tamer->update($tamerData);
            // if($tamer){
            $tamer->tamerCategoryAttributeItems()->sync($validItems);

            if ($request->tamer_search_tags) {
                $tamer_search_tags = $request->input("tamer_search_tags", []);
                $tamer->tamerSearchTags()->sync($tamer_search_tags);
            }
            return response()->json([
                'message' => 'Step no 1 saved Successfully!',
                'tamer_id' => $tamer->id,
            ], 200);

            // }


        } catch (\Exception $e) {
            return $e->getMessage();
            // return response()->json(
            //     [
            //         "error" => "Somethings Errors",

            //     ],
            //     422
            // );
        }
    }

    public function update_stp2(Request $request, $tamer_id)
    {
        $request->validate([

            "tamer_id" => "required|exists:tamers,id",
            "_categoryElement_package.*.category_element_id" => "required|exists:category_elements,id",

            "is_basic" => "accepted",



            "_categoryElement_package.*.basic_pk_element_value" => "required",
            "_categoryElement_package.*.standard_pk_element_value" => "required_if:is_standard,1",
            "_categoryElement_package.*.advanced_pk_element_value" => "required_if:is_advanced,1",

            "basic_package_price" => "required",

            "standard_package_price" => "required_if:is_standard,1",
            "advanced_package_price" => "required_if:is_advanced,1",

        ]);
        try {

            // $tamer = Tamer::findOrFail($tamer_id);
            // $tamer->has_basic_package = $request->input('is_basic');
            // $tamer->has_standard_package = $request->input('is_standard');
            // $tamer->has_advanced_package = $request->input('is_advanced');
            // $tamer->update();


            $tamer = Tamer::findOrFail($tamer_id);
            $tamer->has_basic_package = $request->input('is_basic');
            $tamer->has_standard_package = $request->input('is_standard');
            $tamer->has_advanced_package = $request->input('is_advanced');
            $tamer->basic_package_price = $request->input('basic_package_price');
            $tamer->standard_package_price = $request->input('standard_package_price');
            $tamer->advanced_package_price = $request->input('advanced_package_price');
            $tamer->update();

            $validationMessages = [];


            if ($request->input("_categoryElement_package")) {
                foreach ($request->input("_categoryElement_package") as $key => $pk) {



                    if ($request->input("is_advanced") == 1) {
                        if ($request->input("is_basic") == 0 || $request->input("is_standard") == 0) {
                            return response()->json(['message' => 'fill standard first'], 422);

                        }

                    }
                    if ($pk['id'] != null) {
                        $tamer_category_element = TamerCategoryElement::where('tamer_id', $tamer_id)->where('id', $pk['id'])->get();
                        // dd($tamer_category_element);
                        foreach ($tamer_category_element as $tr) {
                            // dd($tr->id);
                            $tr->category_element_id = $pk["category_element_id"];
                            $tr->basic_pk_element_value = $pk["basic_pk_element_value"];
                            $tr->standard_pk_element_value = $pk["standard_pk_element_value"];
                            $tr->advanced_pk_element_value = $pk["advanced_pk_element_value"];
                            $tr->update();
                        }
                    } else {
                        $tamer_category_element = TamerCategoryElement::create([
                            "tamer_id" => $request->input("tamer_id"),
                            // 'package_type'=>$pk['package_type'],
                            "category_element_id" => $pk["category_element_id"],
                            "basic_pk_element_value" =>
                                $pk["basic_pk_element_value"],
                            "standard_pk_element_value" =>
                                $pk["standard_pk_element_value"],
                            "advanced_pk_element_value" =>
                                $pk["advanced_pk_element_value"],
                        ]);
                    }


                    // dd('Stop');




                }
            }


            if ($request->input("_categoryService_package")) {
                foreach ($request->input("_categoryService_package") as $key => $pk) {
                    if ($pk['id'] != null) {
                        $tamer_category_service = TamerCategoryService::where('tamer_id', $tamer_id)->where('id', $pk['id'])->get();
                        foreach ($tamer_category_service as $tr) {
                            $tr->is_basic_pk = $pk["is_basic_pk"];
                            $tr->is_standard_pk = $pk["is_standard_pk"];
                            $tr->is_advanced_pk = $pk["is_advanced_pk"];
                            $tr->category_service_id = $pk["category_service_id"];
                            $tr->update();
                        }
                    } else {
                        $tamer_category_service = TamerCategoryService::create([
                            "tamer_id" => $request->input("tamer_id"),
                            "is_basic_pk" => $pk["is_basic_pk"],
                            "is_standard_pk" => $pk["is_standard_pk"],
                            "is_advanced_pk" => $pk["is_advanced_pk"],
                            "category_service_id" => $pk["category_service_id"],
                        ]);
                    }


                }
            }


            if ($request->input("_tamer_add_ons")) {

                foreach ($request->input("_tamer_add_ons") as $key => $pk) {


                    if ($pk["add_on_type"] == "customAddOn") {


                        if (!$pk["title_for_custom_add_on"]) {
                            $validationMessages[] =

                                "The Title For Custom Add On is Required" .

                                " at index: " .
                                $key;
                        }
                        if (!$pk["description_for_custom_add_on"]) {
                            $validationMessages[] =

                                "The Description For Custom Add On is Required" .

                                " at index: " .
                                $key;
                        }

                    }
                    if ($pk["add_on_type"] == "categoryService") {
                        if (!$pk["category_eleserv_id"]) {
                            $validationMessages[] =

                                "The Category Service ID is Required" .

                                " at index: " .
                                $key;
                        }
                        // $request->validate([
                        //     "_tamer_add_ons.*.category_eleserv_id" => "required_if:".$pk["add_on_type"].",==,categoryService",

                        // ]);
                    }
                    if ($pk["add_on_type"] == "categoryElement") {
                        if (!$pk["category_eleserv_id"]) {
                            $validationMessages[] =

                                "The Category Element ID is Required" .

                                " at index: " .
                                $key;
                        }

                    }


                    if (!empty ($validationMessages)) {
                        return response()->json(
                            ["errors" => $validationMessages],
                            422
                        );
                    }

                    if ($pk['id'] != null) {
                        $tamer_add_on = TamerAddOn::where('tamer_id', $tamer_id)->where('id', $pk['id'])->get();
                        foreach ($tamer_add_on as $key => $tr) {
                            $tr->add_on_type = $pk["add_on_type"];
                            $tr->category_eleserv_id = $pk["category_eleserv_id"];
                            $tr->additional_days = $pk["additional_days"];
                            $tr->extra_price = $pk["extra_price"];
                            $tr->title_for_custom_add_on = $pk["title_for_custom_add_on"];
                            $tr->description_for_custom_add_on = $pk["description_for_custom_add_on"];
                            $tr->update();
                        }
                    } else {
                        $tamer_add_on = TamerAddOn::create([
                            "tamer_id" => $request->input("tamer_id"),
                            "add_on_type" => $pk["add_on_type"],
                            "category_eleserv_id" => $pk["category_eleserv_id"],
                            "additional_days" => $pk["additional_days"],
                            "extra_price" => $pk["extra_price"],
                            "title_for_custom_add_on" =>
                                $pk["title_for_custom_add_on"],
                            "description_for_custom_add_on" =>
                                $pk["description_for_custom_add_on"],
                        ]);
                    }







                }
            }
        } catch (Exception $ex) {
            return response()->json(
                [
                    "error" => $ex->getMessage(),
                ],
                422
            );
        }
    }

    public function deleteSingleTamerFile($tamer_file_id, $tamer_id)
    {
        try {
            $tamer_file = TamerFile::where('id', $tamer_file_id)->where('tamer_id', $tamer_id)->first();
            // $tamer_file = TamerFile::findOrFail($tamer_file_id);

            if (
                \Storage::disk("s3")->exists("tamer_files/" . $tamer_file->file_url)
            ) {
                \Storage::disk("s3")->delete(
                    "tamer_files/" . $tamer_file->file_url
                );
            }

            $tamer_file->delete();
        } catch (\Exeption $ex) {
            return response()->json(
                [
                    "error" => "Somethings Errors",
                ],
                422
            );
        }
    }

    public function update_stp3(Request $request)
    {
        $this->validate($request, [
            "tamer_id" => "required|exists:tamers,id",
        ]);
        try {
            if (count($request->_files) > 0) {
                foreach ($request->_files as $key => $mydata) {
                    // dd($request->_files);
                    if ($mydata["file_url"]) {
                        $filename =
                            time() .
                            "-" .
                            \Str::random(15) .
                            "-" .
                            $mydata["file_url"]->getClientOriginalName();
                        // $filename = time().'-'. \Str::random(15);
                        $mydata["file_url"]->storeAs(
                            "tamer_files/",
                            $filename,
                            "s3"
                        );
                        $tamer = TamerFile::create([
                            "tamer_id" => $request->input("tamer_id"),
                            "file_type" => $mydata["file_type"],
                            "file_url" => $filename,
                        ]);
                    }
                }
            }
        } catch (\Exeption $ex) {
            return response()->json(
                [
                    "error" => "Somethings Errors",
                ],
                422
            );
        }
    }
    public function update_stp4(Request $request, $tamer_id)
    {
        $this->validate($request, [
            "tamer_id" => "required|exists:tamers,id",
        ]);

        try {
            $validationMessages = [];
            $selected_multiple_choice_no = 0;
            foreach ($request->input("_tamer_requirement") as $key => $req) {
                $this->validate($request, [
                    "_tamer_requirement.*.requirement_text" => "required",
                    "_tamer_requirement.*.answer_type" => "required",
                    "_tamer_requirement.*.is_mandatory_requirement" => "required",
                ]);

                if ($req["answer_type"] == 'multipleChoice') {
                    if (!$req['firstـchoice_text'] || !$req['secondـchoice_text']) {
                        $validationMessages[] =

                            "you have to chose two at least" .

                            " at index: " .
                            $key;
                    }
                    // if()

                }
                // if ($req["index"] != null) {
                //     $this->validate($request, [
                //         "_tamer_requirement.*.requirement_text" => "required",
                //         "_tamer_requirement.*.answer_type" => "required",
                //     ]);
                // }
                // dd('ff');
                if (!empty ($validationMessages)) {
                    return response()->json(
                        ["errors" => $validationMessages],
                        422
                    );
                }
                if ($req["id"] != null) {
                    $tamer_req = TamerRequirement::where('id', $req['id'])->where('tamer_id', $tamer_id)->get();
                    if ($tamer_req) {
                        foreach ($tamer_req as $tmr) {
                            $tmr->requirement_text = $req["requirement_text"];
                            $tmr->answer_type = $req["answer_type"];
                            $tmr->is_mandatory_requirement = $req["is_mandatory_requirement"];
                            $tmr->is_allow_more_than_one_answer_for_multi_choice = $req["is_allow_more_than_one_answer_for_multi_choice"];
                            $tmr->update();
                        }

                    }
                } else {
                    $tamer_req = TamerRequirement::create([
                        "tamer_id" => $request->input("tamer_id"),
                        "requirement_text" => $req["requirement_text"],
                        "answer_type" => $req["answer_type"],
                        "is_mandatory_requirement" =>
                            $req["is_mandatory_requirement"],
                        "is_allow_more_than_one_answer_for_multi_choice" =>
                            $req["is_allow_more_than_one_answer_for_multi_choice"],
                    ]);
                }




            }
        } catch (Exception $ex) {
            return response()->json(
                [
                    "error" => $ex->getMessage()
                ],
                422
            );
        }
    }
    public function update_stp5(Request $request, $tamer_id)
    {
        $this->validate($request, [
            "tamer_id" => "required|exists:tamers,id",
            "description" => "required|string",
        ]);
        try {
            if ($request->input("_tamer_step")) {
                foreach ($request->input("_tamer_step") as $key => $req) {
                    // if ($req["index"] != null) {
                    // dd($req['index']);
                    $this->validate($request, [
                        "_tamer_step.*.name" => "required|string",
                        "_tamer_step.*.description" => "required|string",
                    ]);
                    // }

                    //---








                    if ($req['id'] != null) {
                        $tamer_steps = TamerStep::where('tamer_id', $tamer_id)->where('id', $req['id'])->get();

                        foreach ($tamer_steps as $tr) {
                            // dd($tr->id);
                            $tr->name = $req["name"];
                            $tr->description = $req["description"];

                            $tr->update();
                        }




                    } else {
                        $tamer_step = TamerStep::create([
                            "tamer_id" => $tamer_id,
                            "name" => $req["name"],
                            "description" => $req["description"],
                        ]);
                    }



                }
            }

            if ($request->input("_tamer_question")) {
                foreach ($request->input("_tamer_question") as $key => $qes) {
                    // if ($req["index"] != null) {
                    // dd($req['index']);
                    $this->validate($request, [
                        "_tamer_question.*.question_text" =>
                            "required|string",
                        "_tamer_question.*.answer_text" =>
                            "required|string",
                    ]);
                    // }
                    if ($qes['id'] != null) {
                        $tamer_question = TamerQuestion::where('tamer_id', $tamer_id)->where('id', $qes['id'])->get();

                        foreach ($tamer_question as $tr) {
                            // dd($tr->id);
                            $tr->question_text = $qes["question_text"];
                            $tr->answer_text = $qes["answer_text"];

                            $tr->update();
                        }




                    } else {
                        $tamer = TamerQuestion::create([
                            "tamer_id" => $request->input("tamer_id"),
                            "question_text" => $qes["question_text"],
                            "answer_text" => $qes["answer_text"],
                        ]);
                    }



                }
            }
            $tamer = Tamer::findOrFail($request->input("tamer_id"));

            if ($tamer) {
                $tamer->description = $request->input("description");
                $tamer->update();
            }

        } catch (Exception $ex) {
            return response()->json(
                [
                    "error" => $ex->getMessage(),
                ],
                422
            );
        }
    }

    public function update_stp6(Request $request, $tamer_id)
    {
        $this->validate($request, [
            "tamer_id" => "required|exists:tamers,id",
            "privacy_notice_agreement" => "accepted",
            "term_of_service_agreement" => "accepted",
            "max_no_of_simultaneous_tamers" => "required|numeric|max:3",
        ]);
        try {


            // Finalize - review
            $tamer = Tamer::findOrFail($tamer_id);

            if ($tamer) {
                $tamer->max_no_of_simultaneous_tamers = $request->input(
                    "max_no_of_simultaneous_tamers"
                );
                $tamer->term_of_service_agreement = $request->input(
                    "term_of_service_agreement"
                );
                $tamer->privacy_notice_agreement = $request->input(
                    "privacy_notice_agreement"
                );
                $tamer->status = "underReview";
            }
            $tamer->update();
        } catch (Exception $ex) {
            return response()->json(
                [
                    "error" => $ex->getMessage(),
                    // "error" => "Somethings Errors",
                ],
                422
            );
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Tamer $tamer)
    {
        //
    }
}