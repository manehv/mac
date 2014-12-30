<?php
	class WiziappThemeTaxonomyQuery
	{
		var $terms = array();
		var $term = null;
		var $current = 0;
		var $have_more = false;
		var $type = 'category';

		function query($args)
		{
			$number = '';
			$offset = '';
			if (!empty($args['terms_per_page']) && ($terms_per_page = (int) $args['terms_per_page']) > 0)
			{
				$page = empty($args['page']) ? 1 : (int) $args['page'];
				if ($page < 1)
				{
					$page = 1;
				}
				$offset = $terms_per_page * ($page-1);
				$number = $terms_per_page+1;
			}
			$this->type = empty($args['type']) ? 'category' : $args['type'];
			if (empty($args['post']))
			{
				$this->terms = get_terms($this->type, array(
					'parent' => isset($args['parent'])?$args['parent']:'',
					'orderby' => 'name'
				));
				if (!empty($number))
				{
					$this->terms = array_slice($this->terms, $offset, $number);
				}
			}
			else
			{
				$this->terms = wp_get_post_terms($args['post'], $this->type);
				if (!empty($number))
				{
					$this->terms = array_slice($this->terms, $offset, $number);
				}
			}
			if (!empty($number) && count($this->terms) >= $number)
			{
				$this->have_more = true;
				array_pop($this->terms);
			}
		}

		function getType()
		{
			return $this->type;
		}

		function haveTerms()
		{
			return (count($this->terms) > $this->current);
		}

		function haveMore()
		{
			return $this->have_more;
		}

		function theTerm()
		{
			$this->term = $this->terms[$this->current++];
		}

		function theId()
		{
			echo esc_html($this->term->term_id);
		}

		function theName()
		{
			echo esc_html($this->term->name);
		}

		function theCount($zero, $one, $many)
		{
			if ($this->term->count < 1)
			{
				echo esc_html($zero);
			}
			else if ($this->term->count > 1)
			{
				echo esc_html(str_replace('%', $this->term->count, $many));
			}
			else
			{
				echo esc_html($one);
			}
		}

		function theLink()
		{
			echo esc_html(get_term_link($this->term));
		}
	}
