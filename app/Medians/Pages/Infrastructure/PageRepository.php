<?php

namespace Medians\Pages\Infrastructure;

use Medians\Pages\Domain\Page;

use Medians\Content\Domain\Content;

class PageRepository 
{

	
	/**
	 * Load app for Sessions and helpful
	 * methods for authentication and
	 * settings for branch
	 */ 
	protected $app ;


	function __construct()
	{
		$this->app = new \config\APP;
	}


	public static function getModel()
	{
		return new Page();
	}


	public function find($id, $prefix = null)
	{
		return Page::with(['content'=>function($q) use ($prefix){
			$prefix ? $q->where('prefix', $prefix) : $q->where('lang', $_SESSION['lang']);
		}])->find($id);
	}


	public function homepage()
	{
		return Page::where('home', 1)->with(['content'=>function($q) {
			$q->where('lang', __('lang'));
		}])->first();
	}

	public function get($limit = 100)
	{
		return Page::with('content','user')->limit($limit)->orderBy('id', 'DESC')->get();
	}

	public function getByCategory($id, $limit = 100)
	{
		return Page::with('content','user')->where('category_id', $id)->limit($limit)->orderBy('id', 'DESC')->get();
	}

	public function getFeatured($limit = 1)
	{
		return Page::with('content','user')->orderBy('updated_at', 'DESC')->first();
	}

	public function search($request, $limit = 20)
	{
		$title = $request->get('search');
		$arr =  json_decode(json_encode(['id'=>0, 'content'=>['title'=>$title]]));

		return $this->similar( $arr, $limit);
	}


	public function similar($item, $limit = 3)
	{
		if (empty($item->content->title))
			return null;
		
		$title = str_replace([' ','-'], '%', $item->content->title);

		return Page::whereHas('content', function($q) use ($title){
			foreach (explode('%', $title) as $i) {
				$q->where('title', 'LIKE', '%'.$i.'%')->orWhere('content', 'LIKE', '%'.$i.'%');
			}
		})
		->where('id', '!=', $item->id)
		->with('category', 'content','user')->limit($limit)->orderBy('updated_at', 'DESC')->get();
	}




	/**
	* Save item to database
	*/
	public function store($data) 
	{

		$Model = new Page();
		
		foreach ($data as $key => $value) 
		{
			if (in_array($key, $this->getModel()->getFields()))
			{
				$dataArray[$key] = $value;
			}
		}		

		// Return the FBUserInfo object with the new data
    	$Object = Page::create($dataArray);
    	$Object->update($dataArray);

    	// Store Custom fields
    	$this->storeContent($data['content'], $Object->id);

    	return $Object;
    }
    	


	/**
	* Delete item to database
	*
	* @Returns Boolen
	*/
	public function delete($id) 
	{
		try {
			
			$delete = Page::find($id)->delete();

			if ($delete){
				$this->storeContent(null, $id);
			}

			return true;

		} catch (\Exception $e) {

			throw new \Exception("Error Processing Request " . $e->getMessage(), 1);
			
		}
	}


	/**
	* Save related items to database
	*/
	public function storeContent($data, $id) 
	{
		Content::where('item_type', Page::class)->where('item_id', $id)->delete();
		if ($data)
		{
			foreach ($data as $key => $value)
			{
				$fields = $value;
				$fields['item_type'] = Page::class;	
				$fields['item_id'] = $id;	
				$fields['lang'] = $key;	
				$fields['prefix'] = isset($value['prefix']) ? $value['prefix'] : Content::generatePrefix($value['title']);	
				$fields['created_by'] = $this->app->auth()->id;

				$Model = Content::create($fields);
				$Model->update($fields);
			}
	
			return $Model;		
		}
	}




 
}