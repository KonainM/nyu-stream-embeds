<div class="wrap" lang="en">
    <h1><?php _e( 'NYU Stream Embed', $this->text_domain ) ?></h1>

    <?php
        //Display status message
        if ( isset( $_GET['dmsg'] ) ) { ?>
            <div id="message" class="updated fade"><p><?php echo urldecode( $_GET['dmsg'] ); ?></p></div><?php
        }

        if ( 'network' == $network ): ?>
            <div id="nse-network-settings">
                <p><?php  _e( 'You can customize NYU Stream embed plugin related settings here.', $this->text_domain ); ?></p>
                <p><?php  _e( 'To get going, first <a href="http://www.nyu.edu/servicelink/KB0012363">learn how the plugin works.</a>, find out iframe embed code from NYU stream, and identify various elements in the embed code such as, video id, plalist id, etc.', $this->text_domain ); ?></p>

                <h2 class="title nse-video-settings"><?php _e( 'NYU Stream Embed Settings for Video', $this->text_domain ) ?></h2>

                <form method="post" action="">
                    <table  class="form-table nse-video-settings">

                        <tr class="nse-video-settings" valign="top">
                            <th scope="row"><?php _e( 'iframe id', $this->text_domain ); ?></th>
                            <td>
                                <input aria-label="iframe id entry box" type="text" name="nse_settings_video_id" class="regular-text" <?php if ( !empty( $this->current_settings['nse_settings']['nse_settings_video_id'] ) ) { echo "value=".$this->current_settings['nse_settings']['nse_settings_video_id']; } ?> />
                                <p class="description"><?php _e( 'id from iframe embed code for single video. e.g. kaltura_player', $this->text_domain ); ?></p>
                            </td>
                        </tr>

                        <tr class="nse-video-settings" valign="top">
                            <th scope="row"><?php _e( 'iframe src', $this->text_domain ); ?></th>
                            <td>
                                <textarea aria-label="iframe source entry for videos" name="nse_settings_video_src" rows="5" cols="60"><?php if ( !empty( $this->current_settings['nse_settings']['nse_settings_video_src'] ) ) { echo $this->current_settings['nse_settings']['nse_settings_video_src']; } ?></textarea>
                                <p class="description"><?php _e( 'src from iframe embed code for single video. e.g. https://cdnapisec.kaltura.com/p/1674401/sp/167440100/embedIframeJs/uiconf_id/23435151/partner_id/1674401?iframeembed=true&amp;playerId=kaltura_player&amp;flashvars[mediaProtocol]=rtmp&amp;flashvars[streamerType]=rtmp&amp;flashvars[streamerUrl]=rtmp://www.kaltura.com:1935&amp;flashvars[rtmpFlavors]=1&amp;&amp;wid=1_f8okstds&amp;entry_id=', $this->text_domain ); ?></p>
                            </td>
                        </tr>


                    </table>

                    <h2 class="title nse-playlist-settings"><?php _e( 'NYU Stream Embed Settings for Playlist', $this->text_domain ) ?></h2>

                    <table class="form-table nse-playlist-settings">

                        <tr class="nse-playlist-settings" valign="top">
                            <th scope="row"><?php _e( 'iframe src', $this->text_domain ); ?></th>
                            <td>
                                <textarea aria-label="iframe source entry for playlists" name="nse_settings_playlist_src" rows="5" cols="60"><?php if ( !empty( $this->current_settings['nse_settings']['nse_settings_playlist_src'] ) ) { echo $this->current_settings['nse_settings']['nse_settings_playlist_src']; } ?></textarea>
                                <p class="description"><?php _e( 'src from iframe embed code for playlist. e.g. https://cdnapisec.kaltura.com/p/1674401/sp/167440100/embedIframeJs/uiconf_id/23437711/partner_id/1674401/widget_id/1_zh8d6z1g?iframeembed=true&playerId=kaltura_player_1390404249&flashvars[playlistAPI.autoContinue]=true&flashvars[playlistAPI.autoInsert]=true&flashvars[ks]=&flashvars[playlistAPI.kpl0Id]=', $this->text_domain ); ?></p>
                            </td>
                        </tr>

                    </table>

                    <p class="submit">
                        <?php wp_nonce_field('submit_nse_settings_network'); ?>
                        <input type="submit" name="submit" class="button-primary" value="<?php _e( 'Save Changes', $this->text_domain ); ?>" />
                    </p>

                </form>
            </div>

        <?php else: ?>

            <div id="nse-site-settings">
                <p><?php  _e( 'You can customize NYU Stream embed plugin related settings here.', $this->text_domain ); ?></p>
                <p><?php  _e( 'To get going, first <a href="http://www.nyu.edu/servicelink/KB0012363">learn how the plugin works.</a>, find out iframe embed code from NYU stream, and identify various elements in the embed code such as, video id, plalist id, etc.', $this->text_domain ); ?></p>

                <h2 class="title nse-vid-settings"><?php _e( 'NYU Stream Embed Settings for Video', $this->text_domain ) ?></h2>

                <form method="post" action="">
                    <table  class="form-table nse-vid-settings">

                        <tr class="nse-vid-settings" valign="top">
                            <th scope="row"><?php _e( 'iframe id', $this->text_domain ); ?></th>
                            <td>
                                <input type="text" name="id" class="regular-text" <?php if ( !empty( $this->current_settings['nse_settings_vid']['id'] ) ) { echo "value=".$this->current_settings['nse_settings_vid']['id']; } ?> />
                                <p class="description"><?php _e( 'id from iframe embed code for single video. e.g. kaltura_player', $this->text_domain ); ?></p>
                            </td>
                        </tr>

                        <tr class="nse-vid-settings" valign="top">
                            <th scope="row"><?php _e( 'iframe src', $this->text_domain ); ?></th>
                            <td>
                                <textarea name="src" rows="5" cols="60"><?php if ( !empty( $this->current_settings['nse_settings_vid']['src'] ) ) { echo $this->current_settings['nse_settings_vid']['src']; } ?></textarea>
                                <p class="description"><?php _e( 'src from iframe embed code for single video. e.g. https://cdnapisec.kaltura.com/p/1674401/sp/167440100/embedIframeJs/uiconf_id/23435151/partner_id/1674401?iframeembed=true&amp;playerId=kaltura_player&amp;flashvars[mediaProtocol]=rtmp&amp;flashvars[streamerType]=rtmp&amp;flashvars[streamerUrl]=rtmp://www.kaltura.com:1935&amp;flashvars[rtmpFlavors]=1&amp;&amp;wid=1_f8okstds&amp;entry_id=', $this->text_domain ); ?></p>
                            </td>
                        </tr>


                    </table>

                    <h2 class="title nse-playlist-settings"><?php _e( 'NYU Stream Embed Settings for Playlist', $this->text_domain ) ?></h2>

                    <table class="form-table nse-playlist-settings">

                        <tr class="nse-playlist-settings" valign="top">
                            <th scope="row"><?php _e( 'iframe src', $this->text_domain ); ?></th>
                            <td>
                                <textarea name="src" rows="5" cols="60"><?php if ( !empty( $this->current_settings['nse_settings_playlist']['src'] ) ) { echo $this->current_settings['nse_settings_playlist']['src']; } ?></textarea>
                                <p class="description"><?php _e( 'src from iframe embed code for playlist. e.g. https://cdnapisec.kaltura.com/p/1674401/sp/167440100/embedIframeJs/uiconf_id/23437711/partner_id/1674401/widget_id/1_zh8d6z1g?iframeembed=true&playerId=kaltura_player_1390404249&flashvars[playlistAPI.autoContinue]=true&flashvars[playlistAPI.autoInsert]=true&flashvars[ks]=&flashvars[playlistAPI.kpl0Id]=', $this->text_domain ); ?></p>
                            </td>
                        </tr>

                    </table>

                    <p class="submit">
                        <?php wp_nonce_field('submit_nse_settings'); ?>
                        <input type="submit" name="submit" class="button-primary" value="<?php _e( 'Save Changes', $this->text_domain ); ?>" />
                    </p>
                </form>
            </div>

        <?php endif; ?>
</div>
