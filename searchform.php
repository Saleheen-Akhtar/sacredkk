<?php /** Custom Search Form for Sacred Kompass */ ?>
<form role="search" method="get" class="sk-search-form" action="<?php echo esc_url(home_url('/')); ?>">
    <div class="sk-search-wrapper">
        <label for="sk-search-field" class="screen-reader-text"><?php echo esc_html_x('Search the journal', 'label', 'sacred-kompass'); ?></label>
        <input
            type="search"
            id="sk-search-field"
            class="sk-search-input"
            placeholder="<?php echo esc_attr_x('Search the journal...', 'placeholder', 'sacred-kompass'); ?>"
            value="<?php echo get_search_query(); ?>"
            name="s"
            autocomplete="off"
            spellcheck="false"
        />
        <button type="submit" class="sk-search-submit btn btn-primary"><?php esc_html_e('Search', 'sacred-kompass'); ?></button>
    </div>
</form>
