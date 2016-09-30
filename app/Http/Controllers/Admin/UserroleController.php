<?php
/******************************************
****AuThor:rubbish@163.com
****Title :用户角色
*******************************************/
namespace App\Http\Controllers\Admin;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

//使用Role模型
use App\Http\Model\Role;
use DB;
//使用内存缓存
use Redis;
use Cache;
//使用URL生成地址
use URL;
class UserroleController extends PublicController
{
	/******************************************
	****AuThor:rubbish@163.com
	****Title :列表
	*******************************************/
	public function index()  
	{
		$website=$this->website;
		$website['cursitename']=trans('admin.website_navigation_role');

		$website['apiurl_list']=URL::action('Admin\UserroleController@api_list');
		$website['link_add']=URL::action('Admin\UserroleController@add');
		$website['link_edit']='/admin/userrole/edit/';
		$website['way']='name';
		$wayoption[]=array('text'=>trans('admin.website_userrole_item_name'),'value'=>'name');
		$wayoption[]=array('text'=>trans('admin.website_userrole_item_display_name'),'value'=>'display_name');
		$wayoption[]=array('text'=>trans('admin.website_userrole_item_description'),'value'=>'description');
		$website['wayoption']=json_encode($wayoption);

		return view('admin/userrole/index')->with('website',$website);
	}
	/******************************************
	****AuThor:rubbish@163.com
	****Title :添加
	*******************************************/
	public function add()
	{
		$website=$this->website;
		$website['cursitename']=trans('admin.website_navigation_role');
		$website['apiurl_add']=URL::action('Admin\UserroleController@api_add');
		$website['apiurl_info']=URL::action('Admin\UserroleController@api_info');
		$website['apiurl_edit']=URL::action('Admin\UserroleController@api_edit');
		$website['id']=0;
		return view('admin/userrole/add')->with('website',$website);
	}
	/******************************************
	****AuThor : rubbish@163.com
	****Title  : 编辑信息
	*******************************************/
	public function edit($id)  
	{
		$website=$this->website;
		$website['cursitename']=trans('admin.website_navigation_role');
		$website['apiurl_add']=URL::action('Admin\UserroleController@api_add');
		$website['apiurl_info']=URL::action('Admin\UserroleController@api_info');
		$website['apiurl_edit']=URL::action('Admin\UserroleController@api_edit');
		$website['id']=$id;
		return view('admin/userrole/add')->with('website',$website);
	}
	/******************************************
	****AuThor:rubbish@163.com
	****Title :列表接口
	*******************************************/
	public function api_list(Request $request)  
	{
		$search_field=$request->get('way')?$request->get('way'):'title';
		$keyword=$request->get('keyword');
		if($keyword)
		{
			$list=Role::where($search_field, 'like', '%'.$keyword.'%')->paginate($this->pagesize);
			//分页传参数
			$list->appends(['keyword' => $keyword,'way' =>$search_field])->links();
		}
		else
		{
			$list=Role::paginate($this->pagesize);
			
		}
		if($list)
		{
			$msg_array['status']='1';
			$msg_array['info']=trans('admin.website_get_success');
			$msg_array['is_reload']=0;
			$msg_array['curl']='';
			$msg_array['resource']=$list;
			$msg_array['param_way']=$search_field;
			$msg_array['param_keyword']=$keyword;
		}
		else
		{
			$msg_array['status']='1';
			$msg_array['info']=trans('admin.website_get_empty');
			$msg_array['is_reload']=0;
			$msg_array['curl']='';
			$msg_array['resource']="";
			$msg_array['param_way']=$search_field;
			$msg_array['param_keyword']=$keyword;
		}
        return response()->json($msg_array);
	}
	/******************************************
	****AuThor:rubbish@163.com
	****Title :添加接口
	*******************************************/
	public function api_add(Request $request)  
	{
		$condition['name']=$request->get('name');
		$info_count=Role::where($condition)->count();
		if($info_count)
		{
			$msg_array['status']='0';
			$msg_array['info']=trans('admin.website_add_exit');
			$msg_array['is_reload']=0;
			$msg_array['curl']='';
			$msg_array['resource']="";
			$msg_array['param_way']='';
			$msg_array['param_keyword']='';	
		}
		else
		{
			$params = new Role;
			$params->name = $request->get('name');
			$params->display_name	= $request->get('display_name');
			$params->description	= $request->get('description');

			if ($params->save()) 
			{
				$msg_array['status']='1';
				$msg_array['info']=trans('admin.website_add_success');
				$msg_array['is_reload']=0;
				$msg_array['curl']=URL::action('Admin\UserroleController@index');
				$msg_array['resource']='';
				$msg_array['param_way']='';
				$msg_array['param_keyword']='';
			} 
			else 
			{
				$msg_array['status']='0';
				$msg_array['info']=trans('admin.website_add_failure');
				$msg_array['is_reload']=0;
				$msg_array['curl']='';
				$msg_array['resource']="";
				$msg_array['param_way']='';
				$msg_array['param_keyword']='';	

			}	
		}
        return response()->json($msg_array);

	}
	/******************************************
	****AuThor:rubbish@163.com
	****Title :详情接口
	*******************************************/
	public function api_info(Request $request)  
	{

		$condition['id']=$request->get('id');
		$info=DB::table('roles')->where($condition)->first();
		if($info)
		{
			$msg_array['status']='1';
			$msg_array['info']=trans('admin.website_get_success');
			$msg_array['is_reload']=0;
			$msg_array['curl']='';
			$msg_array['resource']=$info;
			$msg_array['param_way']='';
			$msg_array['param_keyword']='';
		}
		else
		{
			$msg_array['status']='0';
			$msg_array['info']=trans('admin.website_get_empty');
			$msg_array['is_reload']=0;
			$msg_array['curl']='';
			$msg_array['resource']="";
			$msg_array['param_way']='';
			$msg_array['param_keyword']='';
		}
        return response()->json($msg_array);
	}
	/******************************************
	****@AuThor : rubbish@163.com
	****@Title  : 更新数据接口
	****@return : Response
	*******************************************/
	public function api_edit(Request $request)
	{

		$params = Role::find($request->get('id'));
		$params->display_name = $request->get('display_name');
		$params->description = $request->get('description');
		
		if ($params->save()) 
		{
			$msg_array['status']='1';
			$msg_array['info']=trans('admin.website_save_success');
			$msg_array['is_reload']=0;
			$msg_array['curl']=URL::action('Admin\UserroleController@index');
			$msg_array['resource']='';
			$msg_array['param_way']='';
			$msg_array['param_keyword']='';
		} 
		else 
		{
			$msg_array['status']='0';
			$msg_array['info']=trans('admin.website_save_failure');
			$msg_array['is_reload']=0;
			$msg_array['curl']='';
			$msg_array['resource']="";
			$msg_array['param_way']='';
			$msg_array['param_keyword']='';	
		}
		return response()->json($msg_array);
	}
}
