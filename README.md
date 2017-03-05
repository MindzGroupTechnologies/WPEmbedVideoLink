# Embed Video Link

## A WordPress Plugin

There are many plugins out there which can embed a YouTube videos within the post. This pugin doesn't embed a video, but renders the YouTube link into a beautiful more informative snippet. The YouTube thumb is fetched and along with that, a complete detail about the video is displayed.

### Prerequisites
1. **YouTube API Key**  
You are required to generate an API Key from Google Developer Console for YouTube API access.

### Sample Render

![sample][sample]

### Installation
1. **Get Latest Release**  
From the releases tab download the latest release binary.
2. **Install**
   * Login to WordPress Administration
   * Navigate to Plugins > Add New
   * Click on Upload Plugin
   * Choose File and Click Install Now
   * Once installed, Click "Activate" to activate the plugin.
3. **Configuration**
   * Once the plugin is installed, there will be anew menu item created in the left navigation named "Video Link", click on that.
   * In the text box provided, enter the YouTube API Key generated from Google Developer Console.
   * Click save changes to save the settings.

### Usage
It is very simple to use, just insert the shortcode similar to the one show below in the post of page, where you are interested to render the EVL snippet.

```
[evl vid="rNXLe-uMjiQ"]Sample Render[/evl]
```
Here `vid` attribute contains the id extracted from the YouTube video url, for example the id in the above sample is extracted from this url’s highlighted part “https://www.youtube.com/watch?v=**rNXLe-uMjiQ**“

That's it! you are done. Your posts will now show a SEO optemized beautifully rendered YouTube video links.

### Planned Features
- [ ] Add support for Playlist Links
- [ ] Add link for the channel
- [ ] Themes and color customizations
- [ ] options to select which all information to be rendered related to video.

In case you are finding any issues, please let me know by creating a issue here in GitHub.

[sample]: https://www.mindzgrouptech.net/wp-content/uploads/2017/03/evl_sample.png