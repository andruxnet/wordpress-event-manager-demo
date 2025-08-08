<div class="wp-event-display">
  <header class="event-header">
    <h2 class="event-title">
      <?php echo esc_html($event->title); ?>
    </h2>
    <?php
      $categories = get_the_terms($event->id, 'event_category');
      if ($categories): ?>
        <div class="event-categories">
          <?php foreach ($categories as $category): ?>
            <span class="event-category-tag"><?php echo esc_html($category->name); ?></span>
          <?php endforeach; ?>
        </div>
      <?php endif; ?>
  </header>

  <div class="event-meta">
    <div class="event-meta-item">
      <span class="meta-label">
        <?php _e('Date:', Andruxnet\EventManager\Plugin::TEXT_DOMAIN); ?>
      </span>
      <span class="meta-value">
        <?php echo esc_html($event->eventDate->format('F j, Y g:i A')); ?>
      </span>
    </div>

    <div class="event-meta-item">
      <span class="meta-label">
        <?php _e('Location:', Andruxnet\EventManager\Plugin::TEXT_DOMAIN); ?>
      </span>
      <span class="meta-value">
        <?php
          echo esc_html($event->location ?: __('TBD', Andruxnet\EventManager\Plugin::TEXT_DOMAIN));
        ?>
      </span>
    </div>

    <div class="event-meta-item">
      <span class="meta-label">
        <?php _e('Capacity:', Andruxnet\EventManager\Plugin::TEXT_DOMAIN); ?>
      </span>
      <span class="meta-value">
          <?php
            echo $event->capacity > 0
              ? esc_html($event->capacity)
              : __('Unlimited', Andruxnet\EventManager\Plugin::TEXT_DOMAIN);
          ?>
      </span>
    </div>
  </div>

  <?php if (!empty($event->description)): ?>
    <div class="event-description">
      <h3>
        <?php _e('About This Event', Andruxnet\EventManager\Plugin::TEXT_DOMAIN); ?>
      </h3>
      <div class="description-content">
        <?php echo wp_kses_post($event->description); ?>
      </div>
    </div>
  <?php endif; ?>
</div>
