<table class="form-table" role="presentation">
  <tbody>
    <tr>
      <th scope="row">
        <label for="event-date"><?php _e('Event Date', Andruxnet\EventManager\Plugin::TEXT_DOMAIN); ?></label>
      </th>
      <td>
        <input type="date"
               id="event-date"
               name="event-date"
               value="<?php echo esc_attr($date); ?>"
               class="regular-text"
        />
        <p class="description"><?php _e('Select the event date', Andruxnet\EventManager\Plugin::TEXT_DOMAIN); ?></p>
      </td>
    </tr>

    <tr>
      <th scope="row">
        <label for="event-location"><?php _e('Location', Andruxnet\EventManager\Plugin::TEXT_DOMAIN); ?></label>
      </th>
      <td>
        <input type="text"
               id="event-location"
               name="event-location"
               value="<?php echo esc_attr($location); ?>"
               class="regular-text"
               placeholder="<?php esc_attr_e('Enter event location', Andruxnet\EventManager\Plugin::TEXT_DOMAIN); ?>"
        />
        <p class="description"><?php _e('Event venue or address', Andruxnet\EventManager\Plugin::TEXT_DOMAIN); ?></p>
      </td>
    </tr>

    <tr>
      <th scope="row">
        <label for="event-capacity"><?php _e('Capacity', Andruxnet\EventManager\Plugin::TEXT_DOMAIN); ?></label>
      </th>
      <td>
        <input type="number"
               id="event-capacity"
               name="event-capacity"
               value="<?php echo esc_attr($capacity); ?>"
               class="small-text"
               min="1"
        />
        <p class="description"><?php _e('Maximum number of attendees (leave empty for unlimited)', Andruxnet\EventManager\Plugin::TEXT_DOMAIN); ?></p>
      </td>
    </tr>
  </tbody>
</table>
