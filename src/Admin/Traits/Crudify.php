<?php

namespace Arbory\Base\Admin\Traits;

use Arbory\Base\Admin\Exports\Type\ExcelExport;
use Arbory\Base\Admin\Form;
use Arbory\Base\Admin\Grid;
use Arbory\Base\Admin\Grid\ExportBuilder;
use Arbory\Base\Admin\Layout;
use Arbory\Base\Admin\Module;
use Arbory\Base\Admin\Tools\ToolboxMenu;
use Arbory\Base\Html\Html;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

trait Crudify
{
    /**
     * @var Module
     */
    protected $module;

    /**
     * @return Model|Builder
     */
    public function resource()
    {
        $class = $this->resource;

        return new $class;
    }

    /**
     * @return Module
     */
    protected function module()
    {
        if ($this->module === null) {
            $this->module = \Admin::modules()->findModuleByControllerClass(get_class($this));
        }

        return $this->module;
    }

    /**
     * @param Form $form
     * @return Form
     */
    protected function form(Form $form)
    {
        return $form;
    }

    /**
     * @param Model $model
     * @return Form
     */
    protected function buildForm(Model $model)
    {
        $form = new Form($model);
        $form->setModule($this->module());
        $form->setRenderer(new Form\Builder($form));

        return $this->form($form) ?: $form;
    }

    /**
     * @param Grid $grid
     * @return Grid
     */
    public function grid(Grid $grid)
    {
        return $grid;
    }

    /**
     * @param Model $model
     * @return Grid
     */
    protected function buildGrid(Model $model)
    {
        $grid = new Grid($model);
        $grid->setModule($this->module());
        $grid->setRenderer(new Grid\Builder($grid));

        $grid->rowActions(function (ToolboxMenu $toolBox) {
            $model = $toolBox->model();

            $toolBox->add('edit', $this->url('edit', $model->getKey()));
            $toolBox->add('delete',
                $this->url('dialog', ['dialog' => 'confirm_delete', 'id' => $model->getKey()])
            )->dialog()->danger();
        });

        return $this->grid($grid) ?: $grid;
    }

    /**
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $variables = [
            'bodyClass' => 'controller-' . str_slug($this->module()->name()) . ' view-index',
            'breadcrumb' => $this->module()->breadcrumbs(),
            'content' => $this->buildGrid($this->resource()),
        ];

        return view('arbory::controllers.resource.index', $variables);
    }

    /**
     * @param $resourceId
     * @return \Illuminate\Http\RedirectResponse
     */
    public function show($resourceId)
    {
        return redirect($this->module()->url('edit', $resourceId));
    }

    /**
     * @return Layout
     */
    public function create()
    {
        $variables = [
            'bodyClass' => 'controller-' . str_slug($this->module()->name()) . ' view-edit',
            'breadcrumb' => $this->module()->breadcrumbs(),
            'content' => $this->buildForm($this->resource())->render(),
        ];

        return view('arbory::controllers.resource.form', $variables);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store(Request $request)
    {
        $form = $this->buildForm($this->resource());

        $request->request->add(['fields' => $form->fields()]);

        $form->validate();

        if ($request->ajax()) {
            return response()->json(['ok']);
        }

        $form->store($request);

        return $this->getAfterEditResponse($request);
    }

    /**
     * @param $resourceId
     * @return Layout
     */
    public function edit($resourceId)
    {
        $resource = $this->findOrNew($resourceId);
        $breadcrumb = $this->module()->breadcrumbs();

        $variables = [
            'bodyClass' => 'controller-' . str_slug($this->module()->name()) . ' view-edit',
            'breadcrumb' => $breadcrumb,
            'content' => $this->buildForm($resource)->render(),
        ];

        return view('arbory::controllers.resource.form', $variables);
    }

    /**
     * @param Request $request
     * @param $resourceId
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function update(Request $request, $resourceId)
    {
        $resource = $this->findOrNew($resourceId);
        $form = $this->buildForm($resource);

        $request->request->add(['fields' => $form->fields()]);

        $form->validate();

        if ($request->ajax()) {
            return response()->json(['ok']);
        }

        $form->update($request);

        return $this->getAfterEditResponse($request);
    }

    /**
     * @param $resourceId
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function destroy($resourceId)
    {
        $resource = $this->resource()->findOrFail($resourceId);

        $this->buildForm($resource)->destroy();

        return redirect($this->module()->url('index'));
    }

    /**
     * @param Request $request
     * @param $name
     * @return mixed
     */
    public function dialog(Request $request, $name)
    {
        $method = camel_case($name) . 'Dialog';

        if (!$name || !method_exists($this, $method)) {
            app()->abort(Response::HTTP_NOT_FOUND);

            return null;
        }

        return $this->{$method}($request);
    }


    /**
     * @param string $type
     * @return mixed
     */
    public function export($type)
    {
        $grid = $this->buildGrid($this->resource());
        $grid->setRenderer(new ExportBuilder($grid));
        $grid->paginate(false);

        $dataSet = $grid->render();

        switch ($type) {
            case 'xls':
                return \Excel::download(new ExcelExport($dataSet), $this->module()->name() . '.xlsx');
                break;

            case 'json':
            default:
                return response()->json($dataSet->getItems());
                break;
        }
    }

    /**
     * @param Request $request
     * @return string
     */
    protected function toolboxDialog(Request $request)
    {
        $node = $this->findOrNew($request->get('id'));

        $toolbox = new ToolboxMenu($node);

        $this->toolbox($toolbox);

        return $toolbox->render();
    }

    /**
     * @param \Arbory\Base\Admin\Tools\ToolboxMenu $tools
     */
    protected function toolbox(ToolboxMenu $tools)
    {
        $model = $tools->model();

        $tools->add('edit', $this->url('edit', $model->getKey()));
        $tools->add('delete',
            $this->url('dialog', ['dialog' => 'confirm_delete', 'id' => $model->getKey()])
        )->dialog()->danger();
    }

    /**
     * @param Request $request
     * @return \Illuminate\View\View
     */
    protected function confirmDeleteDialog(Request $request)
    {
        $resourceId = $request->get('id');
        $model = $this->resource()->find($resourceId);

        return view('arbory::dialogs.confirm_delete', [
            'form_target' => $this->url('destroy', [$resourceId]),
            'list_url' => $this->url('index'),
            'object_name' => (string)$model,
        ]);
    }

    /**
     * @param Request $request
     * @param $name
     * @return null
     */
    public function api(Request $request, $name)
    {
        $method = camel_case($name) . 'Api';

        if (!$name || !method_exists($this, $method)) {
            app()->abort(Response::HTTP_NOT_FOUND);

            return null;
        }

        return $this->{$method}($request);
    }

    /**
     * @param $route
     * @param array $parameters
     * @return string
     */
    public function url($route, $parameters = [])
    {
        return $this->module()->url($route, $parameters);
    }

    /**
     * @param mixed $resourceId
     * @return Model
     */
    protected function findOrNew($resourceId): Model
    {
        /**
         * @var Model $resource
         */
        $resource = $this->resource();

        if (method_exists($resource, 'bootSoftDeletes')) {
            $resource = $resource->withTrashed();
        }

        $resource = $resource->findOrNew($resourceId);
        $resource->setAttribute($resource->getKeyName(), $resourceId);

        return $resource;
    }

    /**
     * @param Request $request
     * @return array|Request|string
     */
    public function slugGeneratorApi(Request $request)
    {
        /** @var \Illuminate\Database\Query\Builder $query */
        $slug = str_slug($request->input('from'));
        $column = $request->input('column_name');

        $query = \DB::table($request->input('model_table'))->where($column, $slug);

        if ($locale = $request->input('locale')) {
            $query->where('locale', $locale);
        }

        if ($objectId = $request->input('object_id')) {
            $query->where('id', '<>', $objectId);
        }

        if ($column && $query->exists()) {
            $slug .= '-' . random_int(0, 9999);
        }

        return $slug;
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    protected function getAfterEditResponse(Request $request)
    {
        return redirect($request->has('save_and_return') ? $this->module()->url('index') : $request->url());
    }
}
