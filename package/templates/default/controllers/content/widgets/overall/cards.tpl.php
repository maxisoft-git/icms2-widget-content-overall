<?php if ($items) { ?>
	<?php $this->addCSS('templates/'.$this->name.'/controllers/content/widgets/overall/assets/css/styles.css');?>
    <div class="widget_content__list overall-cards">
		<?php foreach ($items as $item) { ?>

			<?php
			$url          = href_to($item['ctype']['name'], $item['slug'] . '.html');
			$image_field  = $options[$item['ctype']['name'] . '_photo'];
			$teaser_field = $options[$item['ctype']['name'] . '_teaser'];
            $teaser_len = $options['limit_teaser'] ? $options['limit_teaser'] : 0;
			$image      = ($image_field && !empty($item[$image_field])) ? $item[$image_field] : '';

			$image = !$image && $options['show_placeholder'] ? default_images('no-photo','normal') : $image;

			?>

            <div class="card item">

                <?php if ($image) { ?>
                    <div class="image">
						<?php if ($url) { ?>
                            <a href="<?php echo $url; ?>">
                                <?php echo html_image($image, 'normal', $item['title']);?>
                            </a>
						<?php } else { ?>
							<?php echo html_image($image, 'normal', $item['title']); ?>
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
                </div>
                    <div class="details">
                        <?php if ($options['show_author']) { ?>
                            <span class="author">
                                <a href="<?php echo href_to('users', $item['user']['id']); ?>"><?php html($item['user']['nickname']); ?></a>
                            </span>
                        <?php } ?>
	                    <?php if ($options['show_group'] && $item['parent_id']) { ?>
                            <span class="author">
                                <?php echo LANG_WROTE_IN_GROUP; ?>
                                <a href="<?php echo rel_to_href($item['parent_url']); ?>"><?php html($item['parent_title']); ?></a>
                            </span>
	                    <?php } ?>
	                    <?php if ($options['show_pubdate']) { ?>
                            <span class="date">
                                <?php echo string_date_format($item['date_pub'], false); ?>
                            </span>
                        <?php } ?>
                    </div>
            </div>
		<?php } ?>
    </div>
<?php } ?>
