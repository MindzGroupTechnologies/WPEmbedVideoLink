<blockquote class="evl">
    <div class="row">
        <div class="col-md-12 vid_message"><?php echo $this->content; ?></div>
        <div class="col-md-5 col-sm-5">
            <a target="_blank" href="https://www.youtube.com/watch?v='.$this->vid.'">
                <img src="https://i.ytimg.com/vi/<?php echo $this->vid ?>/maxresdefault.jpg"/>
            </a>
        </div>
        <div class="col-md-7 col-sm-7">
            <div class="row">
                <div class="col-md-12 vid_title">
                    <a target="_blank" href="https://www.youtube.com/watch?v='.$this->vid.'"><?php echo $vid_info->items[0]->snippet->title ?></a>
                </div>
                <div class="col-md-12 vid_description">
                    description: <strong><?php echo $vid_info->items[0]->snippet->description?></strong>
                </div>
                <div class="col-md-12 vid_info">
                    duration: <strong><?php echo $this->parseYouTubeDuration($vid_info->items[0]->contentDetails->duration) ?></strong>
                </div>
                <div class="col-md-12 vid_statistics">
                    views: <strong><?php echo $vid_info->items[0]->statistics->viewCount ?></strong>
                </div>
                <div class="col-md-12 vid_channel">
                    by: <strong><?php echo $vid_info->items[0]->snippet->channelTitle ?></strong>
                </div>
                <div class="col-md-12 vid_source">
                    source: <strong>YouTube</strong>
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