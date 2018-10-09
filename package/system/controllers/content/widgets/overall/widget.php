<?php

	class widgetContentOverall extends cmsWidget
	{
		private $model;

		public function run()
		{
			$this->model = cmsCore::getModel('content');
			$ctypes      = $this->model->getContentTypes();

			$is_cache = $this->getOption('enable_cache');

			if (!$is_cache) {
				$this->disableCache();
			}

			$items = [];

			foreach ($ctypes as $ctype) {
				$is_enable = $this->getOption($ctype['name'] . '_enable');
				if ($is_enable) {
					$records = $this->getContent($ctype);
					if ($records) {
						$items += $records;
					}
				}
			}

			$is_shuffle = $this->getOption('enable_shuffle');

			switch ($this->options['sort']) {
				case 'date':
					usort($items, function ($a, $b) {
						return strtotime($b['date_pub']) - strtotime($a['date_pub']);
					});
					break;

				case 'ctype':
					usort($items, function ($a, $b) {
						return strcmp($a['ctype']['title'], $b['ctype']['title']);
					});
					break;

				case 'title':
					usort($items, function ($a, $b) {
						return strcmp($a['title'], $b['title']);
					});
					break;

				default:
					usort($items, function ($a, $b) {
						return strtotime($b['date_pub']) - strtotime($a['date_pub']);
					});
					break;
			}

			$limit_records = $this->getOption('limit_records');
			if ($limit_records) {
				$items = array_slice($items, 0, $limit_records);
			}

			if ($is_shuffle) {
				shuffle($items);
			}

			return [
				'items'   => $items,
				'options' => $this->options,
			];
		}

		private function getContent($ctype)
		{
			$dataset_id = $this->getOption($ctype['name'] . '_dataset', null);
			$cat_id     = $this->getOption($ctype['name'] . '_category');
			$limit      = $this->getOption($ctype['name'] . '_limit', 10);
			$category   = null;
			$dataset    = null;

			if ($cat_id) {
				$category = $this->model->getCategory($ctype['name'], $cat_id);
				if ($category) {
					$this->model->filterCategory($ctype['name'], $category, true);
				}
			}

			if ($dataset_id) {
				$dataset = $this->model->getContentDataset($dataset_id);
				if ($dataset) {
					$this->model->applyDatasetFilters($dataset);
				}
			}

			$this->model->enableHiddenParentsFilter();

			list($ctype, $this->model) = cmsEventsManager::hook("content_list_filter", [$ctype, $this->model]);
			list($ctype, $this->model) = cmsEventsManager::hook("content_{$ctype['name']}_list_filter", [$ctype, $this->model]);

			if ($ctype['is_cats']) {
				$this->model->select('ct.slug', 'cat_slug')
				            ->select('ct.title', 'cat_title')
				            ->joinLeft($this->model->table_prefix . $ctype['name'] . '_cats', 'ct', 'ct.id=i.category_id');
			}

			$items = $this->model->limit($limit)
			                     ->getContentItems($ctype['name'], function ($item) use ($ctype) {
				                     $item['ctype'] = [
					                     'id'          => $ctype['id'],
					                     'title'       => $ctype['title'],
					                     'name'        => $ctype['name'],
					                     'is_comments' => $ctype['is_comments'],
				                     ];

				                     return $item;
			                     });
			if (!$items) {
				return false;
			}

			list($ctype, $items) = cmsEventsManager::hook("content_before_list", [$ctype, $items]);
			list($ctype, $items) = cmsEventsManager::hook("content_{$ctype['name']}_before_list", [$ctype, $items]);

			return $items;
		}

	}
