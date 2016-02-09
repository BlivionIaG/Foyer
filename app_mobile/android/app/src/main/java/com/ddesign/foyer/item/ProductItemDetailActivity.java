package com.ddesign.foyer.item;

import android.content.Context;
import android.content.Intent;
import android.graphics.Bitmap;
import android.graphics.Canvas;
import android.graphics.ColorMatrix;
import android.graphics.ColorMatrixColorFilter;
import android.graphics.drawable.BitmapDrawable;
import android.graphics.drawable.Drawable;
import android.os.Bundle;
import android.support.design.widget.FloatingActionButton;
import android.support.design.widget.Snackbar;
import android.support.v4.app.NavUtils;
import android.support.v7.app.AppCompatActivity;
import android.support.v7.widget.Toolbar;
import android.text.Html;
import android.view.MenuItem;
import android.view.View;
import android.widget.ImageView;

import com.ddesign.foyer.R;
import com.ddesign.foyer.dummy.Cart;
import com.ddesign.foyer.dummy.CommandItem;
import com.ddesign.foyer.dummy.Content;
import com.ddesign.foyer.dummy.Item;
import com.ddesign.foyer.dummy.ProduitItem;


/**
 * An activity representing a single Item detail screen. This
 * activity is only used on handset devices. On tablet-size devices,
 * item details are presented side-by-side with a list of items
 * in a {@link ItemListActivity}.
 * <p/>
 * This activity is mostly just a 'shell' activity containing nothing
 * more than a {@link ProductItemDetailFragment}.
 */
public class ProductItemDetailActivity extends AppCompatActivity {

    // a changer en Item pour regrouper avec CommandeItem
    private ProduitItem item;
    private ProductItemDetailFragment fragment;

    public static Drawable convertBitmapToDrawable(Context context, Bitmap bitmap) {
        Drawable d = new BitmapDrawable(context.getResources(), bitmap);
        return d;
    }

    public static Bitmap convertDrawableToBitmap(Drawable drawable) {
        if (drawable instanceof BitmapDrawable) {
            return ((BitmapDrawable) drawable).getBitmap();
        }

        Bitmap bitmap = Bitmap.createBitmap(drawable.getIntrinsicWidth(),
                drawable.getIntrinsicHeight(), Bitmap.Config.ARGB_8888);
        Canvas canvas = new Canvas(bitmap);
        drawable.setBounds(0, 0, canvas.getWidth(), canvas.getHeight());
        drawable.draw(canvas);

        return bitmap;
    }

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_item_detail);

        this.item = (ProduitItem) Content.PRODUCT_ITEMS.get(Integer.parseInt(getIntent().getStringExtra(ProductItemDetailFragment.ARG_ITEM_ID)));

        Drawable drawable = item.getDrawable();

        Cart.initCartFab(this);

        if(drawable!=null){
            Drawable drawable_bar = convertBitmapToDrawable(this,convertDrawableToBitmap(drawable));
            ColorMatrix colorScale = new ColorMatrix();
            colorScale.setScale((float)(255/211), 0f, 0f, 1);
            ColorMatrixColorFilter filter = new ColorMatrixColorFilter(colorScale);
            ImageView iv = (ImageView) findViewById(R.id.detail_image);
            drawable_bar.setColorFilter(filter);
            iv.setImageDrawable(drawable_bar);
        }

        Toolbar toolbar = (Toolbar) findViewById(R.id.detail_toolbar);
        setSupportActionBar(toolbar);

        final FloatingActionButton fab_add = (FloatingActionButton) findViewById(R.id.fab_add);
        fab_add.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                if(item.isAvailable()) {
                    Snackbar.make(view, Html.fromHtml("1 <b>" + item.getContent().toLowerCase() + "</b> " +
                                    getString(R.string.text_item_add)),
                            Snackbar.LENGTH_LONG).setAction("Action", null).show();

                    Cart.addItem(item);
                }else{
                    Snackbar.make(view, Html.fromHtml(getString(R.string.text_item_not_available)),
                            Snackbar.LENGTH_LONG).setAction("Action", null).show();
                }
            }
        });




        // Show the Up button in the action bar.
        getSupportActionBar().setDisplayHomeAsUpEnabled(true);

        // savedInstanceState is non-null when there is fragment state
        // saved from previous configurations of this activity
        // (e.g. when rotating the screen from portrait to landscape).
        // In this case, the fragment will automatically be re-added
        // to its container so we don't need to manually add it.
        // For more information, see the Fragments API guide at:
        //
        // http://developer.android.com/guide/components/fragments.html
        //
        if (savedInstanceState == null) {
            // Create the detail fragment and add it to the activity
            // using a fragment transaction.
            Bundle arguments = new Bundle();
            arguments.putString(ProductItemDetailFragment.ARG_ITEM_ID,
                    getIntent().getStringExtra(ProductItemDetailFragment.ARG_ITEM_ID));
            fragment = new ProductItemDetailFragment();
            fragment.setArguments(arguments);
            getSupportFragmentManager().beginTransaction()
                    .add(R.id.item_detail_container, fragment)
                    .commit();
        }
    }

    @Override
    public boolean onOptionsItemSelected(MenuItem item) {
        int id = item.getItemId();
        if (id == android.R.id.home) {
            // This ID represents the Home or Up button. In the case of this
            // activity, the Up button is shown. Use NavUtils to allow users
            // to navigate up one level in the application structure. For
            // more details, see the Navigation pattern on Android Design:
            //
            // http://developer.android.com/design/patterns/navigation.html#up-vs-back
            //
            NavUtils.navigateUpTo(this, new Intent(this, ItemListActivity.class));
            return true;
        }
        return super.onOptionsItemSelected(item);
    }
}
