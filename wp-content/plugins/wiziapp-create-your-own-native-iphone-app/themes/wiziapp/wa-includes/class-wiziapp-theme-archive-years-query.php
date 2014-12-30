<?php
	class WiziappThemeArchiveYearsQuery
	{
		var $archives = array();
		var $archive = null;
		var $current = 0;
		var $have_more = false;

		function query($args)
		{
			$number = '';
			$offset = '';
			if (!empty($args['archives_per_page']) && ($per_page = (int) $args['archives_per_page']) > 0)
			{
				$page = empty($args['page']) ? 1 : (int) $args['page'];
				if ($page < 1)
				{
					$page = 1;
				}
				$offset = $per_page * ($page-1);
				$number = $per_page+1;
			}

			/*
			 * Unfortunately, Wordpress provides no pre-made function for this.
			 * Short of querying all the posts, and sifting through the dates manually,
			 * this is the only way.
			 */
			$cache_key = empty($number)?'archive_years':"archive_years:$offset:$number";
			$this->archives = wp_cache_get($cache_key, 'wiziapp_theme');
			if (false === $this->archives)
			{
				global $wpdb;
				$limit = empty($number)?'':(' LIMIT '.$offset.','.$number);
				$query = "SELECT YEAR(post_date) AS `year`, count(ID) as count FROM $wpdb->posts WHERE `post_type`='post' AND `post_status`='publish' GROUP BY YEAR(post_date) ORDER BY post_date DESC $limit";
				$this->archives = $wpdb->get_results($query);
				wp_cache_add($cache_key, $this->archives, 'wiziapp_theme');
			}

			if (!empty($number) && count($this->archives) >= $number)
			{
				$this->have_more = true;
				array_pop($this->archives);
			}
		}

		function haveArchives()
		{
			return (count($this->archives) > $this->current);
		}

		function haveMore()
		{
			return $this->have_more;
		}

		function theArchive()
		{
			$this->archive = $this->archives[$this->current++];
		}

		function theYear()
		{
			echo esc_html($this->archive->year);
		}

		function theCount($zero, $one, $many)
		{
			if ($this->archive->count < 1)
			{
				echo esc_html($zero);
			}
			else if ($this->archive->count > 1)
			{
				echo esc_html(str_replace('%', $this->archive->count, $many));
			}
			else
			{
				echo esc_html($one);
			}
		}

		function theLink()
		{
			echo wiziapp_theme_settings()->getMonthsUrl($this->archive->year);
		}
	}
