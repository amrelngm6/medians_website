<?php

namespace Medians\Blog\Infrastructure;

use Medians\Blog\Domain\Blog;
use Medians\Blog\Domain\Content;
use Medians\CustomFields\Domain\CustomField;


class BlogRepository 
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
		foreach ($this->get(100) as $key => $value) 
		{
			// (new \Medians\Media\Infrastructure\MediaRepository)->resize($value->picture, 270, 224);
		}

	}


	public static function getModel()
	{
		return new Blog();
	}


	public function find($id)
	{
		return Blog::with('content')->find($id);
	}

	public function get($limit = 100)
	{
		return Blog::with('content','user')->with(['category'=>function($q){
			return $q->with('content');
		}])->limit($limit)->orderBy('id', 'DESC')->get();
	}

	public function getByCategory($id, $limit = 100)
	{
		return Blog::with('content','user')->where('category_id', $id)->limit($limit)->orderBy('id', 'DESC')->get();
	}

	public function getFeatured($limit = 1)
	{
		return Blog::with('content','user')->orderBy('updated_at', 'DESC')->first();
	}

	public function search($request, $limit = 20)
	{
		$title = $request->get('search');
		$arr =  json_decode(json_encode(['id'=>0, 'content'=>['title'=>$title ? $title : '-']]));

		return $this->similar( $arr, $limit);
	}


	public function similar($item, $limit = 3)
	{
		$title = str_replace([' ','-'], '%', $item->content->title);

		return Blog::whereHas('content', function($q) use ($title){
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

		$Model = new Blog();
		
		foreach ($data as $key => $value) 
		{
			if (in_array($key, $this->getModel()->getFields()))
			{
				$dataArray[$key] = $value;
			}
		}		

		// Return the FBUserInfo object with the new data
    	$Object = Blog::create($dataArray);
    	$Object->update($dataArray);

    	// Store languages content
    	$this->storeContent($data['content'], $Object->id);

    	// Store Custom fields
    	$this->storeCustomFields($data['field'], $Object->id);

    	return $Object;
    }
    	
    /**
     * Update Lead
     */
    public function update($data)
    {

		$Object = Blog::find($data['id']);
		
		// Return the FBUserInfo object with the new data
    	$Object->update( (array) $data);

    	// Store languages content
    	!empty($data['content']) ? $this->storeContent($data['content'], $data['id']) : '';

    	// Store Custom fields
    	!empty($data['field']) ? $this->storeCustomFields($data['field'], $data['id']) : '';

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
			
			$delete = Blog::find($id)->delete();

			if ($delete){
				$this->storeContent(null, $id);
				$this->storeCustomFields(null, $id);
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
		Content::where('item_type', Blog::class)->where('item_id', $id)->delete();
		if ($data)
		{
			foreach ($data as $key => $value)
			{
				$fields = $value;
				$fields['item_type'] = Blog::class;	
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

	/**
	* Save related items to database
	*/
	public function storeCustomFields($data, $id) 
	{
		CustomField::where('item_type', Blog::class)->where('item_id', $id)->delete();
		if ($data)
		{
			foreach ($data as $key => $value)
			{
				$fields = [];
				$fields['item_type'] = Blog::class;	
				$fields['item_id'] = $id;	
				$fields['code'] = $key;	
				$fields['value'] = $value;

				$Model = CustomField::create($fields);
				$Model->update($fields);
			}
	
			return $Model;		
		}
	}


 
}