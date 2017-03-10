        <div class="evl">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <div class="col-xs-offset-2 col-xs-10">
                            <h2>EVL Vimeo Configurations</h2>
                        </div>
                    </div>
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <form class="form-horizontal" action="options.php" method="post">
                            <?php settings_fields( 'evl_config_vimeo' )?>
                            <?php do_settings_sections( 'evl_config_vimeo' )?>
                            <div class="form-group">
                                <label for="evl_vimeo_client_id" class="control-label col-xs-2">Client ID</label>
                                <div class="col-xs-10">
                                    <input type="text" class="form-control" id="evl_vimeo_client_id" name="evl_vimeo_client_id" value="<?php echo get_option( "evl_vimeo_client_id", "" ) ?>" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="evl_vimeo_client_secret" class="control-label col-xs-2">Cliend Secret</label>
                                <div class="col-xs-10">
                                    <input type="text" class="form-control" id="evl_vimeo_client_secret" name="evl_vimeo_client_secret" value="<?php echo get_option( "evl_vimeo_client_secret", "" ) ?>" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-xs-2">Access Token</label>
                                <div class="col-xs-10">
                                    <input class="form-control" readonly value="<?php echo ($this->hasVimeoAccessToken()?'You have got access token':'Please provide valid Vimeo App Credential') ?>" />
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
