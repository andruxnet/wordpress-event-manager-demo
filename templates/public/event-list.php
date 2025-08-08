<div class="wp-event-list">
  <header class="event-list-header">
    <h2>
      <?php _e('Upcoming Events', Andruxnet\EventManager\Plugin::TEXT_DOMAIN); ?>
    </h2>
  </header>

  <?php if (empty($events)): ?>
    <div class="no-events-message">
      <p>
        <?php _e('No upcoming events found.', Andruxnet\EventManager\Plugin::TEXT_DOMAIN); ?>
      </p>
    </div>
  <?php else: ?>
    <div class="events-grid">
      <?php foreach ($events as $event): ?>
        <article class="event-card">
          <div class="event-card-header">
            <h3 class="event-card-title">
              <?php echo esc_html($event->title); ?>
            </h3>

            <?php
              $categories = get_the_terms($event->id, 'event_category');
              if ($categories && !is_wp_error($categories)): ?>
                <div class="event-card-categories">
                  <?php foreach ($categories as $category): ?>
                    <span class="event-category-badge">
                      <?php echo esc_html($category->name); ?>
                    </span>
                  <?php endforeach; ?>
                </div>
              <?php endif; ?>
          </div>

          <div class="event-card-meta">
            <div class="event-meta-row">
              <span class="dashicons dashicons-calendar-alt meta-icon"></span>
              <span class="meta-text">
                <?php echo esc_html($event->eventDate->format('M j, Y')); ?>
              </span>
            </div>

            <?php if (!empty($event->location)): ?>
              <div class="event-meta-row">
                <span class="dashicons dashicons-location meta-icon"></span>
                <span class="meta-text">
                  <?php echo esc_html($event->location); ?>
                </span>
              </div>
            <?php endif; ?>

            <div class="event-meta-row">
              <span class="dashicons dashicons-groups meta-icon"></span>
              <span class="meta-text">
                <?php
                  echo $event->capacity > 0
                    ? sprintf(__('%d spots', Andruxnet\EventManager\Plugin::TEXT_DOMAIN), $event->capacity)
                    : __('Unlimited', Andruxnet\EventManager\Plugin::TEXT_DOMAIN);
                ?>
              </span>
            </div>
          </div>

          <?php if (!empty($event->description)): ?>
            <div class="event-card-excerpt">
              <p>
                <?php echo wp_trim_words(wp_kses_post($event->description), 20); ?>
              </p>
            </div>
          <?php endif; ?>

        </article>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>
</div>
