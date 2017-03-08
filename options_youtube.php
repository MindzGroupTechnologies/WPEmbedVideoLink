        <div class="evl">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <div class="col-xs-offset-2 col-xs-10">
                            <h2>EVL YouTube Configurations</h2>
                        </div>
                    </div>
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <form class="form-horizontal" action="options.php" method="post">
                            <?php settings_fields( 'evl_config_youtube' )?>
                            <?php do_settings_sections( 'evl_config_youtube' )?>
                            <div class="form-group">
                                <label for="evl_youtube_api_key" class="control-label col-xs-2">Google API Key</label>
                                <div class="col-xs-10">
                                    <input type="text" class="form-control" id="evl_youtube_api_key" name="evl_youtube_api_key" value="<?php echo get_option( "evl_youtube_api_key", "" ) ?>" />
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-xs-offset-2 col-xs-10">
                                    <?php submit_button()?>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
