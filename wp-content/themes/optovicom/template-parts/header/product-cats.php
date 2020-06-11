<div class="header-categories container">
  <?php
  $q_obj = get_queried_object();
  $prod_cats = get_terms('taxonomy=product_cat&hide_empty=0&parent=0');

  if (!empty($prod_cats)) : ?>
    <ul class="prod-categories">

      <?php foreach ($prod_cats as $cat) :
        $su_menu_class = !empty(get_term_children($cat->term_id, 'product_cat')) ? ' term-has-children' : '';
        $class = $q_obj && $q_obj->term_id == $cat->term_id ? ' current-category' : '';
        $p_class = $q_obj && $q_obj->parent == $cat->term_id ? ' current-category-parent' : ''; ?>

        <li class="prod-category category-<?= $cat->term_id . $class . $p_class . $su_menu_class; ?>">
          <a href="<?= get_term_link($cat); ?>"><?= $cat->name; ?></a>

          <?php
          $child_cats = get_terms("taxonomy=product_cat&parent={$cat->term_id}");

          if (!empty($child_cats)) : ?>
            <span class="expand-chevron"></span>
            <ul class="sub-categories">

              <?php foreach ($child_cats as $child_cat) :
                $child_class = $q_obj && $q_obj->term_id == $child_cat->term_id ? ' current-category' : ''; ?>

                <li class="prod-category category-<?= $child_cat->term_id . $child_class; ?>">
                  <a href="<?= get_term_link($child_cat); ?>"><?= $child_cat->name; ?></a>
                </li>

              <?php endforeach; ?>

            </ul>
          <?php endif; ?>
        </li>

      <?php endforeach; ?>

    </ul>
  <?php endif; ?>
</div>
