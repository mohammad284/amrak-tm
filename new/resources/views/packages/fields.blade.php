
<div class="d-flex flex-column col-sm-12 col-md-6">
    <!-- Name Field -->
    <div class="form-group align-items-baseline d-flex flex-column flex-md-row">
        {!! Form::label('name', trans("lang.package_name"), ['class' => 'col-md-3 control-label text-md-right mx-1']) !!}
        <div class="col-md-9">
            {!! Form::text('name', null,  ['class' => 'form-control','placeholder'=>  trans("lang.package_name_placeholder")]) !!}
            <div class="form-text text-muted">
                {{ trans("lang.package_name_help") }}
            </div>
        </div>
    </div>

    <div class="form-group align-items-baseline d-flex flex-column flex-md-row">
        {!! Form::label('name', trans("lang.package_price"), ['class' => 'col-md-3 control-label text-md-right mx-1']) !!}
        <div class="col-md-9">
            {!! Form::text('price', null,  ['class' => 'form-control','placeholder'=>  trans("lang.package_price_placeholder")]) !!}
            <div class="form-text text-muted">
                {{ trans("lang.package_price_help") }}
            </div>
        </div>
    </div>
    <!-- Parent Id Field -->
    <div class="form-group align-items-baseline d-flex flex-column flex-md-row">
        {!! Form::label('name', trans("lang.package_period"), ['class' => 'col-md-3 control-label text-md-right mx-1']) !!}
        <div class="col-8">
            {!! Form::select('period',
            [
            'week'    => trans('lang.app_setting_week_theme'),
            'month'   => trans('lang.app_setting_month_theme'),
            '6 month' => trans('lang.app_setting_6_month_theme'),
            'year'    => trans('lang.app_setting_year_theme'),
            ]
            , setting('period'), ['class' => 'select2 form-control']) !!}
            <div class="form-text text-muted">{{ trans("lang.package_period_help") }}</div>
        </div>
    </div>
    <!-- Description Field -->
    <div class="form-group align-items-baseline d-flex flex-column flex-md-row">
        {!! Form::label('description', trans("lang.package_description"), ['class' => 'col-md-3 control-label text-md-right mx-1']) !!}
        <div class="col-md-9">
            {!! Form::textarea('description', null, ['class' => 'form-control','placeholder'=>
             trans("lang.package_description_placeholder")  ]) !!}
            <div class="form-text text-muted">{{ trans("lang.package_description_help") }}</div>
        </div>
    </div>

</div>


</div>
<!--  -->
<!-- Submit Field -->
<div class="form-group col-12 d-flex flex-column flex-md-row justify-content-md-end justify-content-sm-center border-top pt-4">

    <button type="submit" class="btn bg-{{setting('theme_color')}} mx-md-3 my-lg-0 my-xl-0 my-md-0 my-2">
        <i class="fa fa-save"></i> {{trans('lang.save')}} {{trans('lang.package')}}
    </button>
    <a href="{!! route('packages.index') !!}" class="btn btn-default"><i class="fa fa-undo"></i> {{trans('lang.cancel')}}</a>
</div>
