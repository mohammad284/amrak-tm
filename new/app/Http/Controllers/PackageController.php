<?php
/*
 * File name: CategoryController.php
 * Last modified: 2021.01.25 at 15:38:34
 * Author: SmarterVision - https://codecanyon.net/user/smartervision
 * Copyright (c) 2021
 */

namespace App\Http\Controllers;

use App\DataTables\PackageDataTable;
use App\DataTables\SubscriptDataTable;
use App\Http\Requests\CreatePackageRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Repositories\CategoryRepository;
use App\Repositories\CustomFieldRepository;
use App\Repositories\UploadRepository;
use Exception;
use Flash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;
use Prettus\Validator\Exceptions\ValidatorException;
use App\Models\Package;
use App\Models\UserSubscrip;
class PackageController extends Controller
{
    /** @var  CategoryRepository */
    private $categoryRepository;

    /**
     * @var CustomFieldRepository
     */
    private $customFieldRepository;

    /**
     * @var UploadRepository
     */
    private $uploadRepository;

    // public function __construct(CategoryRepository $categoryRepo, CustomFieldRepository $customFieldRepo, UploadRepository $uploadRepo)
    // {
    //     parent::__construct();
    //     $this->categoryRepository = $categoryRepo;
    //     $this->customFieldRepository = $customFieldRepo;
    //     $this->uploadRepository = $uploadRepo;
    // }

    /**
     * Display a listing of the Category.
     *
     * @param CategoryDataTable $categoryDataTable
     * @return Response
     */
    public function index(PackageDataTable $packageDataTable)
    {
        return $packageDataTable->render('packages.index');
    }

    /**
     * Show the form for creating a new Category.
     *
     * @return Response
     */
    public function create()
    {

        return view('packages.create');
    }

    /**
     * Store a newly created Category in storage.
     *
     * @param CreatePackageRequest $request
     *
     * @return Response
     */
    public function store(CreatePackageRequest $request)
    {
        
        $input = $request->all();
        try {
            $data = [
                'name'   => $request->name,
                'price'  => $request->price,
                'period' => $request->period,
                'description'=>$request->description
            ];
            Package::create($data);
        } catch (ValidatorException $e) {
            Flash::error($e->getMessage());
        }

        Flash::success(__('lang.saved_successfully', ['operator' => __('lang.Package')]));

        return redirect(route('packages.index'));
    }

    /**
     * Display the specified Category.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $category = $this->categoryRepository->findWithoutFail($id);

        if (empty($category)) {
            Flash::error('Category not found');

            return redirect(route('categories.index'));
        }

        return view('categories.show')->with('category', $category);
    }

    /**
     * Show the form for editing the specified Category.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        
        $package = Package::find($id);
        // dd($category);
        // $parentCategory = $this->categoryRepository->pluck('name', 'id')->prepend(__('lang.category_parent_id_placeholder'), '');

        if (empty($package)) {
            Flash::error(__('lang.not_found', ['operator' => __('lang.Package')]));

            return redirect(route('categories.index'));
        }

        $customFields = Package::find($id);
        return view('packages.edit')->with('package', $package)->with("customFields", isset($html) ? $html : false);
    }

    /**
     * Update the specified Category in storage.
     *
     * @param int $id
     * @param UpdateCategoryRequest $request
     *
     * @return Response
     */
    public function update($id, Request $request)
    {
        $package = Package::find($id);

        if (empty($package)) {
            Flash::error('package not found');
            return redirect(route('package.index'));
        }
        $input = $request->all();
        // dd($request);
        // $customFields = $this->customFieldRepository->findByField('custom_field_model', $this->categoryRepository->model());
        try {
            $data = [
                'price' => $request->price,
                'name'  => $request->name,
                'period'=> $request->period,
                'description'=>$request->description
            ];
            $package->update($data);
        } catch (ValidatorException $e) {
            Flash::error($e->getMessage());
        }

        Flash::success(__('lang.updated_successfully', ['operator' => __('lang.Package')]));

        return redirect(route('packages.index'));
    }

    /**
     * Remove the specified Category from storage.
     *
     * @param int $id
     *
     * @return Response
     */
    public function destroy($id)
    {

        // $this->categoryRepository->delete($id);
            $package = Package::find($id);
            $package->delete();
        Flash::success(__('lang.deleted_successfully', ['operator' => __('lang.category')]));

        return redirect(route('packages.index'));
    }

    /**
     * Remove Media of Category
     * @param Request $request
     */
    public function removeMedia(Request $request)
    {
        $input = $request->all();
        $category = $this->categoryRepository->findWithoutFail($input['id']);
        try {
            if ($category->hasMedia($input['collection'])) {
                $category->getFirstMedia($input['collection'])->delete();
            }
        } catch (Exception $e) {
            Log::error($e->getMessage());
        }
    }
    public function packageSubscript(SubscriptDataTable $SubscriptDataTable)
    {
        return $SubscriptDataTable->render('packages.index');
    }

}
