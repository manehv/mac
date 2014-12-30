<?php
	class WiziappThemeBookmarksQuery
	{
		var $bookmarks = array();
		var $bookmark = null;
		var $current = 0;
		var $have_more = false;

		function query($args)
		{
			$number = '';
			$offset = '';
			if (!empty($args['bookmarks_per_page']) && ($per_page = (int) $args['bookmarks_per_page']) > 0)
			{
				$page = empty($args['page']) ? 1 : (int) $args['page'];
				if ($page < 1)
				{
					$page = 1;
				}
				$offset = $per_page * ($page-1);
				$number = $per_page+1;
			}
			$categories = get_terms('link_category', array(
				'orderby' => 'name',
				'order' => 'ASC',
				'number' => $number?$offset+$number:'',
				'hierarchical' => 0)
			);
			$skip = $offset;
			$this->bookmarks = array();
			foreach ($categories as $category)
			{
				if ($skip > 0)
				{
					$skip--;
				}
				else
				{
					$category->is_category = true;
					$this->bookmarks[] = $category;
					if (!empty($number) && count($this->bookmarks) >= $number)
					{
						break;
					}
				}
				$bookmarks = get_bookmarks(apply_filters('wiziapp_mobile_get_bookmarks', array(
					'limit' => $number?$skip+$number-count($this->bookmarks):-1,
					'category' => $category->term_id
				)));
				if ($skip >= count($bookmarks))
				{
					$skip -= count($bookmarks);
				}
				else
				{
					$bookmarks = array_slice($bookmarks, $skip);
					if (!empty($number) && count($this->bookmarks)+count($bookmarks) > $number)
					{
						$bookmarks = array_slice($bookmarks, 0, $number-count($this->bookmarks));
					}
					$skip = 0;
					foreach ($bookmarks as $bookmark)
					{
						$bookmark->is_category = false;
						$this->bookmarks[] = $bookmark;
					}
					if (!empty($number) && count($this->bookmarks) >= $number)
					{
						break;
					}
				}
			}
			if (!empty($number) && count($this->bookmarks) >= $number)
			{
				$this->have_more = true;
				array_pop($this->bookmarks);
			}
		}

		function haveBookmarks()
		{
			return (count($this->bookmarks) > $this->current);
		}

		function haveMore()
		{
			return $this->have_more;
		}

		function theBookmark()
		{
			$this->bookmark = $this->bookmarks[$this->current++];
		}

		function theId()
		{
			echo esc_html($this->bookmark->is_category?$this->bookmark->term_id:$this->bookmark->link_id);
		}

		function theName()
		{
			echo esc_html($this->bookmark->is_category?$this->bookmark->name:$this->bookmark->link_name);
		}

		function theLink()
		{
			echo esc_html($this->bookmark->link_url);
		}

		function isCategory()
		{
			return $this->bookmark->is_category;
		}
	}
