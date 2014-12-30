<?php
	class WiziappThemeCommentsQuery
	{
		var $comments = array();
		var $comments_count = 0;
		var $comment = null;
		var $current = 0;
		var $threaded = false;
		var $have_more = false;

		function query($args)
		{
			$number = '';
			$offset = '';
			if (!empty($args['comments_per_page']) && ($per_page = (int) $args['comments_per_page']) > 0)
			{
				$page = empty($args['page']) ? 1 : (int) $args['page'];
				if ($page < 1)
				{
					$page = 1;
				}
				$offset = $per_page * ($page-1);
				$number = $per_page+1;
			}
			$this->threaded = isset($args['parent']) || !empty($args['threaded']);
			$this->comments = get_comments(array(
				'post_id' => isset($args['post'])?$args['post']:0,
				'status' => 'approve',
				'order' => isset($args['order']) ? $args['order'] : '',
				'parent' => isset($args['parent']) ? $args['parent'] : (empty($args['threaded']) ? '' : '0'),
				'number' => $number,
				'offset' => $offset
			));
			$this->comments_count = get_comments(array(
				'post_id' => isset($args['post'])?$args['post']:0,
				'status' => 'approve',
				'parent' => isset($args['parent']) ? $args['parent'] : (empty($args['threaded']) ? '' : '0'),
				'count' => true
			));
			if (!empty($number) && count($this->comments) >= $number)
			{
				$this->have_more = true;
				array_pop($this->comments);
			}
		}

		function set($comments, $have_more = false, $comments_count = false)
		{
			$this->comments = $comments;
			$this->comments_count = ($comments_count === false)?count($this->comments):$comments_count;
			$this->have_more = $have_more;
		}

		function getCount()
		{
			return $this->comments_count;
		}

		function haveComments()
		{
			return (count($this->comments) > $this->current);
		}

		function haveMore()
		{
			return $this->have_more;
		}

		function theComment()
		{
			$this->comment = $this->comments[$this->current++];
			if ($this->threaded && !isset($this->comment->reply_count))
			{
				$this->comment->reply_count = get_comments(array(
					'parent' => $this->comment->comment_ID,
					'count' => true
				));
			}
		}

		function theId()
		{
			echo esc_html($this->comment->comment_ID);
		}

		function theAuthor()
		{
			echo esc_html($this->comment->comment_author);
		}

		function theAvatar($size = 96)
		{
			$avatar = get_avatar($this->comment, $size);
			if (!empty($avatar))
			{
				echo $avatar;
			}
		}

		function theDate($format = '')
		{
			comment_date($format, $this->comment);
		}

		function theTime($format = '')
		{
			global $comment;
			// This ugly ugly hack is needed because comment_time does not accept a comment ID as a parameter (Why?)
			if (isset($comment))
			{
				$temp = $comment;
			}
			$comment = $this->comment;
			if (is_object($comment))
			{
				comment_time($format);
			}
			if (isset($temp))
			{
				$comment = $temp;
			}
			else
			{
				unset($comment);
			}
		}

		function theText()
		{
			comment_text($this->comment);
		}

		function theLink()
		{
			$added_query_string = array('wiziapp_comment' => $this->comment->comment_ID, 'background' => ((isset($_GET['background']) && $_GET['background'] === '1') ? '0' : '1'),);
			wiziapp_theme_settings()->prepare_added_query($added_query_string);
			echo esc_html(add_query_arg($added_query_string, trailingslashit(get_bloginfo('url'))));
		}

		function theReplyLink()
		{
			$added_query_string = array('wiziapp_display' => 'comment',);
			wiziapp_theme_settings()->prepare_added_query($added_query_string);
			echo esc_html(add_query_arg($added_query_string, add_query_arg('wiziapp_comment', $this->comment->comment_ID, trailingslashit(get_bloginfo('url')))));
		}

		function getReplyCount()
		{
			return isset($this->comment->reply_count)?$this->comment->reply_count:false;
		}
	}
