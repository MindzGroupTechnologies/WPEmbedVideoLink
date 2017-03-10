<blockquote class="evl">
    <div class="row">
        <div class="col-md-12 vid_message"><?php echo $this->content; ?></div>
        <div class="col-md-5 col-sm-5">
            <a target="_blank" href="<?php echo $vid_info->link; ?>">
                <img src="<?php echo $vid_info->pictures->sizes[sizeof($vid_info->pictures->sizes) - 1]->link; ?>"/>
            </a>
        </div>
        <div class="col-md-7 col-sm-7">
            <div class="row">
                <div class="col-md-12 vid_title">
                    <a target="_blank" href="<?php echo $vid_info->link; ?>"><?php echo $vid_info->name ?></a>
                </div>
                <div class="col-md-12 vid_description">
                    description: <strong><?php echo $vid_info->description?></strong>
                </div>
                <div class="col-md-12 vid_info">
                    duration: <strong><?php echo $this->parseVimeoDuration($vid_info->duration) ?></strong>
                </div>
                <div class="col-md-12 vid_statistics">
                    views: <strong><?php echo $vid_info->stats->plays ?></strong>
                </div>
                <div class="col-md-12 vid_channel">
                    by: <strong><?php echo $vid_info->user->name ?></strong>
                </div>
                <div class="col-md-12 vid_source">
                    source: <strong>Vimeo</strong>
                </div>
            </div>
        </div>
    </div>
    <?php if ($this->promote=='true') { ?>
    <span class="promote">
        Get this plugin <a href="https://www.mindzgrouptech.net/wordpress-plugin-embed-video-link">here</a>.
    </span>
    <?php } ?>
</blockquote>