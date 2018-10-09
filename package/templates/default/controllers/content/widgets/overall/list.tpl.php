<?php if ($items) { ?>
	<?php $this->addCSS('templates/'.$this->name.'/controllers/content/widgets/overall/assets/css/styles.css');?>
    <div class="widget_content__list overall-list">
		<?php foreach ($items as $item) { ?>

			<?php
			$url          = href_to($item['ctype']['name'], $item['slug'] . '.html');
			$image_field  = $options[$item['ctype']['name'] . '_photo'];
			$teaser_field = $options[$item['ctype']['name'] . '_teaser'];
            $teaser_len = $options['limit_teaser'] ? $options['limit_teaser'] : 0;
			$image      = ($image_field && !empty($item[$image_field])) ? $item[$image_field] : '';

			$image = !$image && $options['show_placeholder'] ? default_images('no-photo','small') : $image;

			?>

            <div class="item">
				<?php if ($image) { ?>
                    <div class="image">
						<?php if ($url) { ?>
                            <a href="<?php echo $url; ?>">
                                <?php echo html_image($image, 'small', $item['title']);?>
                            </a>
						<?php } else { ?>
							<?php echo html_image($image, 'small', $item['title']); ?>
						<?php } ?>
                    </div>
				<?php } ?>
                <div class="info">
                    <div class="title">
						<?php if ($url) { ?>
                            <a href="<?php echo $url; ?>"><?php html($item['title']); ?></a>
						<?php } else { ?>
							<?php html($item['title']); ?>
						<?php } ?>
                    </div>
					<?php if ($teaser_field && !empty($item[$teaser_field])) { ?>
                        <div class="teaser">
                            <?php if ($teaser_len) { ?>
							    <?php echo string_short($item[$teaser_field], $teaser_len); ?>
                            <?php } else { ?>
	                            <?php echo $item[$teaser_field]; ?>
                            <?php } ?>
                        </div>
					<?php } ?>
                    <div class="details">
                        <?php if ($options['show_author']) { ?>
                            <span class="author">
                                <a href="<?php echo href_to('users', $item['user']['id']); ?>"><?php html($item['user']['nickname']); ?></a>
                                <?php if ($options['show_group'] && $item['parent_id']) { ?>
                                    <?php echo LANG_WROTE_IN_GROUP; ?>
                                    <a href="<?php echo rel_to_href($item['parent_url']); ?>"><?php html($item['parent_title']); ?></a>
                                <?php } ?>
                            </span>
                        <?php } ?>
	                    <?php if ($options['show_pubdate']) { ?>
                            <span class="date">
                                <?php html(string_date_age_max($item['date_pub'], true)); ?>
                            </span>
                        <?php } ?>
	                    <?php if ($options['show_ctype']) { ?>
                            <span class="ctype">
                                <a href="<?php echo href_to($item['ctype']['name']); ?>"><?php echo $item['ctype']['title'];?></a>
                            </span>
	                    <?php } ?>
	                    <?php if ($options['show_category'] && $item['category_id'] > 1) { ?>
                            <span class="category">
                                <a href="<?php echo href_to($item['ctype']['name'],isset($item['cat_slug']) ? $item['cat_slug'] : ''); ?>"><?php echo $item['cat_title'];?></a>
                            </span>
	                    <?php } ?>
						<?php if ($item['ctype']['is_comments'] && $options['show_comment']) { ?>
                            <span class="comments">
                                    <?php if ($url) { ?>
                                        <a href="<?php echo $url . '#comments'; ?>" title="<?php echo LANG_COMMENTS; ?>">
                                            <?php echo (int) $item['comments']; ?>
                                        </a>
                                    <?php } else { ?>
	                                    <?php echo (int) $item['comments']; ?>
                                    <?php } ?>
                                </span>
						<?php } ?>
                    </div>
                </div>
            </div>
		<?php } ?>
    </div>
<?php } ?>
