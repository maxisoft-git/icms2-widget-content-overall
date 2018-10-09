<?php

	class formWidgetContentOverallOptions extends cmsForm
	{
		public function init($options = false)
		{
			$content_model = cmsCore::getModel('content');
			$ctypes        = $content_model->getContentTypes();

			$opt = [
				'general' => [
					'type'   => 'fieldset',
					'title'  => LANG_OPTIONS,
					'childs' => [
						new fieldList('options:sort', [
							'title'   => LANG_CONTENT_OVERALL_SORT,
							'items'   => [
								'date'  => LANG_CONTENT_OVERALL_SORT_DATE,
								'ctype' => LANG_CONTENT_OVERALL_SORT_CTYPE,
								'title' => LANG_CONTENT_OVERALL_SORT_TITLE,
							],
							'default' => 'date',
							'rules'   => [
								['required'],
							],
						]),
						new fieldNumber('options:limit_title', [
							'title'   => LANG_CONTENT_OVERALL_LIMIT_TITLE,
							'default' => null,
						]),
						new fieldNumber('options:limit_teaser', [
							'title'   => LANG_CONTENT_OVERALL_LIMIT_TEASER,
							'hint'    => LANG_CONTENT_OVERALL_HINT_FORMAT_DELETE,
							'default' => null,
						]),
						new fieldNumber('options:limit_records', [
							'title'   => LANG_CONTENT_OVERALL_LIMIT_RECORDS,
							'hint'    => LANG_CONTENT_OVERALL_HINT_LIMIT_RECORDS,
							'default' => null,
						]),
						new fieldCheckbox('options:show_author', [
							'title'   => LANG_CONTENT_OVERALL_SHOW_AUTHOR,
							'default' => true,
						]),
						new fieldCheckbox('options:show_group', [
							'title'   => LANG_CONTENT_OVERALL_SHOW_GROUP,
							'default' => true,
						]),
						new fieldCheckbox('options:show_pubdate', [
							'title'   => LANG_CONTENT_OVERALL_SHOW_PUBLISHED_DATE,
							'default' => true,
						]),
						new fieldCheckbox('options:show_comment', [
							'title'   => LANG_CONTENT_OVERALL_SHOW_COMMENT,
							'default' => true,
						]),
						new fieldCheckbox('options:show_placeholder', [
							'title'   => LANG_CONTENT_OVERALL_SHOW_PLACEHOLDER,
							'default' => true,
						]),
						new fieldCheckbox('options:show_ctype', [
							'title'   => LANG_CONTENT_OVERALL_SHOW_CTYPE,
							'default' => true,
						]),
						new fieldCheckbox('options:show_category', [
							'title'   => LANG_CONTENT_OVERALL_SHOW_CATEGORY,
							'default' => true,
						]),
						new fieldCheckbox('options:enable_shuffle', [
							'title'   => LANG_CONTENT_OVERALL_ENABLE_SHUFFLE,
							'default' => true,
						]),
						new fieldCheckbox('options:enable_cache', [
							'title'   => LANG_CONTENT_OVERALL_ENABLE_CACHE,
							'default' => true,
						]),
					],
				],
			];

			if ($ctypes) {
				foreach ($ctypes as $ctype) {

					$cats_list     = [];
					$datasets_list = ['0' => ''];
					$fields_list   = ['' => ''];

					$cats = $content_model->getCategoriesTree($ctype['name']);

					if ($cats) {
						foreach ($cats as $cat) {
							if ($cat['ns_level'] > 1) {
								$cat['title'] = str_repeat('-', $cat['ns_level']) . ' ' . $cat['title'];
							}
							$cats_list[$cat['id']] = $cat['title'];

						}
					}

					$datasets = $content_model->getContentDatasets($ctype['id']);
					if ($datasets) {
						$datasets_list = ['0' => ''] + array_collection_to_list($datasets, 'id', 'title');
					}

					$fields = $content_model->getContentFields($ctype['name']);
					if ($fields) {
						$fields_list = ['' => ''] + array_collection_to_list($fields, 'name', 'title');
					}

					$opt['ctype_' . $ctype['name']] = [
						'type'   => 'fieldset',
						'title'  => $ctype['title'],
						'childs' => [
							new fieldCheckbox('options:' . $ctype['name'] . '_enable', [
								'title'   => LANG_CONTENT_OVERALL_CTYPE_ENABLE,
								'default' => true,
							]),
							new fieldList('options:' . $ctype['name'] . '_category', [
								'title' => LANG_CONTENT_OVERALL_CTYPE_CATEGORY,
								'items' => $cats_list,
							]),
							new fieldList('options:' . $ctype['name'] . '_dataset', [
								'title' => LANG_CONTENT_OVERALL_CTYPE_DATASET,
								'items' => $datasets_list,
							]),

							new fieldList('options:' . $ctype['name'] . '_teaser', [
								'title'   => LANG_CONTENT_OVERALL_CTYPE_TEASER,
								'default' => '',
								'items'   => $fields_list,
							]),
							new fieldList('options:' . $ctype['name'] . '_photo', [
								'title'   => LANG_CONTENT_OVERALL_CTYPE_PHOTO,
								'default' => '',
								'items'   => $fields_list,
							]),
							new fieldNumber('options:' . $ctype['name'] . '_limit', [
								'title'   => LANG_CONTENT_OVERALL_CTYPE_LIMIT,
								'default' => '10',
							]),
						],
					];
				}
			}

			return $opt;
		}

	}
