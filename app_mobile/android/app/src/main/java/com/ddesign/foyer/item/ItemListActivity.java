package com.ddesign.foyer.item;

import android.content.Intent;
import android.os.Bundle;
import android.support.v4.app.Fragment;
import android.support.v7.app.AppCompatActivity;
import android.view.Menu;
import android.view.MenuInflater;
import android.view.MenuItem;

import com.ddesign.foyer.JSON.Save;
import com.ddesign.foyer.R;
import com.ddesign.foyer.SettingsActivity;
import com.ddesign.foyer.dummy.Cart;
import com.ddesign.foyer.dummy.Content;
import com.ddesign.foyer.login.LoginActivity;

import java.util.Vector;

/**
 * An activity representing a list of Items. This activity
 * has different presentations for handset and tablet-size devices. On
 * handsets, the activity presents a list of items, which when touched,
 * lead to a {@link ProductItemDetailActivity} representing
 * item details. On tablets, the activity presents the list of items and
 * item details side-by-side using two vertical panes.
 * <p/>
 * The activity makes heavy use of fragments. The list of items is a
 * {@link ItemListFragment} and the item details
 * (if present) is a {@link ProductItemDetailFragment}.
 * <p/>
 * This activity also implements the required
 * {@link ItemListFragment.Callbacks} interface
 * to listen for item selections.
 */
public class ItemListActivity extends AppCompatActivity
        implements ItemListFragment.Callbacks {

    private Vector<MenuItem> menuItems;
    private boolean mTwoPane;
    private Save save;

    @Override
    public boolean onCreateOptionsMenu(Menu menu) {
        // Inflate the menu items for use in the action bar
        MenuInflater inflater = getMenuInflater();
        inflater.inflate(R.menu.action_bar, menu);
        menuItems = new Vector<MenuItem>();
        for(int i=0; i<menu.size(); i++)
            menuItems.add(menu.getItem(i));
        return super.onCreateOptionsMenu(menu);
    }

    @Override
    public boolean onOptionsItemSelected(MenuItem item) {
        // Handle item selection
        switch (item.getItemId()) {
            case R.id.action_settings:
                Intent i = new Intent(this,SettingsActivity.class);
                startActivity(i);
                return true;
            case R.id.action_logout:
                Intent i2 = new Intent(this,LoginActivity.class);
                startActivity(i2);
                i2.putExtra("logout", true);
                this.finishActivity(RESULT_OK);
                this.finish();
                return true;
            case R.id.action_commandes:
            case R.id.action_produits:
                onMenuClick(item);
                System.out.println("action_commandes");
                ((ItemListFragment) getSupportFragmentManager()
                        .findFragmentById(R.id.item_list)).switchList();
                return true;
            default:
                return super.onOptionsItemSelected(item);
        }
    }


    public void onMenuClick(MenuItem item) {
        item.setVisible(false);
        for(MenuItem item1 : menuItems)
            if(!item.equals(item1))
                item1.setVisible(true);
    }


    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_item_app_bar);

        Cart cart = new Cart();
        cart.initCartFab(this);

        save = new Save(this);
        save.read();

        if(save.getUsername().length() > 0){
            return;
        }

        if (findViewById(R.id.item_detail_container) != null) {
            // The detail container view will be present only in the
            // large-screen layouts (res/values-large and
            // res/values-sw600dp). If this view is present, then the
            // activity should be in two-pane mode.
            mTwoPane = true;

            // In two-pane mode, list items should be given the
            // 'activated' state when touched.
            ((ItemListFragment) getSupportFragmentManager()
                    .findFragmentById(R.id.item_list))
                    .setActivateOnItemClick(true);
        }
        // TODO: If exposing deep links into your app, handle intents here.
    }

    /**
     * Callback method from {@link ItemListFragment.Callbacks}
     * indicating that the item with the given ID was selected.
     */
    @Override
    public void onItemSelected(String id) {

        if (mTwoPane) {
            // In two-pane mode, show the detail view in this activity by
            // adding or replacing the detail fragment using a
            // fragment transaction.
            Bundle arguments = new Bundle();
            arguments.putString(ProductItemDetailFragment.ARG_ITEM_ID, id);

            Fragment fragment = null;
            if(Content.SELECTED == Content.PRODUCT_SELECTED)
                fragment = new ProductItemDetailFragment();
            else if(Content.SELECTED == Content.COMMAND_SELECTED)
                fragment = new CommandItemDetailFragment();
            fragment.setArguments(arguments);
            getSupportFragmentManager().beginTransaction()
                    .replace(R.id.item_detail_container, fragment)
                    .commit();

        } else {
            // In single-pane mode, simply start the detail activity
            // for the selected item ID.
            Intent detailIntent = null;
            if(Content.SELECTED == Content.PRODUCT_SELECTED) {
                detailIntent = new Intent(this, ProductItemDetailActivity.class);
                detailIntent.putExtra(ProductItemDetailFragment.ARG_ITEM_ID, id);
            }else if(Content.SELECTED == Content.COMMAND_SELECTED) {
                detailIntent = new Intent(this, CommandItemDetailActivity.class);
                detailIntent.putExtra(CommandItemDetailFragment.ARG_ITEM_ID, id);
            }

            startActivity(detailIntent);
        }
    }




}
