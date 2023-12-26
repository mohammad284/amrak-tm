<?php
/*
 * File name: CategoryDataTable.php
 * Last modified: 2021.04.12 at 09:17:55
 * Author: SmarterVision - https://codecanyon.net/user/smartervision
 * Copyright (c) 2021
 */

namespace App\DataTables;

use App\Models\Package;
use App\Models\CustomField;
use App\Models\Post;
use Barryvdh\DomPDF\Facade as PDF;
use Yajra\DataTables\DataTableAbstract;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder;
use Yajra\DataTables\Services\DataTable;

class PackageDataTable extends DataTable
{
    /**
     * custom fields columns
     * @var array
     */
    public static $customFields = [];

    /**
     * Build DataTable class.
     *
     * @param mixed $query Results from query() method.
     * @return DataTableAbstract
     */
    public function dataTable($query)
    {
        $dataTable = new EloquentDataTable($query);
        $columns = array_column($this->getColumns(), 'data');
        $dataTable = $dataTable
            // ->editColumn('image', function ($package) {
            //     return getMediaColumn($package, 'image');
            // })
            // ->editColumn('description', function ($package) {
            //     return getStripedHtmlColumn($package, 'description');
            // })
            ->editColumn('name', function ($package) {
                return $package->name;
            })
            // ->editColumn('color', function ($package) {
            //     return getColorColumn($package, 'color');
            // })
            // ->editColumn('featured', function ($package) {
            //     return getBooleanColumn($package, 'featured');
            // })
            // ->editColumn('parent_category.name', function ($category) {
            //     return getLinksColumnByRouteName([$category->parentCategory], 'categories.edit', 'id', 'name');
            // })
            ->editColumn('updated_at', function ($package) {
                return getDateColumn($package, 'updated_at');
            })
            ->addColumn('action', 'packages.datatables_actions')
            ->rawColumns(array_merge($columns, ['action']));

        return $dataTable;
    }

    /**
     * Get columns.
     *
     * @return array
     */
    protected function getColumns()
    {
        $columns = [
            // [
            //     'data' => 'image',
            //     'title' => trans('lang.category_image'),
            //     'searchable' => false, 'orderable' => false, 'exportable' => false, 'printable' => false,
            // ],
            [
                'data' => 'name',
                'title' => trans('lang.package_name'),

            ],
            [
                'data' => 'price',
                'title' => trans('lang.package_price'),

            ],
            [
                'data' => 'period',
                'title' => trans('lang.package_period'),

            ],
            [
                'data' => 'description',
                'title' => trans('lang.package_description'),

            ],
            // [
            //     'data' => 'featured',
            //     'title' => trans('lang.category_featured'),
            // ],
            // [
            //     'data' => 'order',
            //     'title' => trans('lang.category_order'),
            // ],
            // [
            //     'data' => 'parent_category.name',
            //     'name' => 'parentCategory.name',
            //     'title' => trans('lang.category_parent_id'),
            //     'searchable' => false, 'orderable' => false,
            // ],
            [
                'data' => 'updated_at',
                'title' => trans('lang.category_updated_at'),
                'searchable' => false,
            ]
        ];

        $hasCustomField = in_array(Package::class, setting('custom_field_models', []));
        
        if ($hasCustomField) {
            $customFieldsCollection = CustomField::where('custom_field_model', Package::class)->where('in_table', '=', true)->get();
            foreach ($customFieldsCollection as $key => $field) {
                array_splice($columns, $field->order - 1, 0, [[
                    'data' => 'custom_fields.' . $field->name . '.view',
                    'title' => trans('lang.package_' . $field->name),
                    'orderable' => false,
                    'searchable' => false,
                ]]);
            }
        }
        return $columns;
    }

    /**
     * Get query source of dataTable.
     *
     * @param Package $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Package $model)
    {
        return $model->newQuery();
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return Builder
     */
    public function html()
    {
        return $this->builder()
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->addAction(['width' => '80px', 'printable' => false, 'responsivePriority' => '100'])
            ->parameters(array_merge(
                config('datatables-buttons.parameters'), [
                    'language' => json_decode(
                        file_get_contents(base_path('resources/lang/' . app()->getLocale() . '/datatable.json')
                        ), true)
                ]
            ));
    }

    /**
     * Export PDF using DOMPDF
     * @return mixed
     */
    public function pdf()
    {
        $data = $this->getDataForPrint();
        $pdf = PDF::loadView($this->printPreview, compact('data'));
        return $pdf->download($this->filename() . '.pdf');
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    // protected function filename()
    // {
    //     return 'categoriesdatatable_' . time();
    // }
}
