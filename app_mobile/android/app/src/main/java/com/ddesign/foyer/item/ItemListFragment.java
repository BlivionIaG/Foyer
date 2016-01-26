package com.ddesign.foyer.item;

import android.app.Activity;
import android.content.Intent;
import android.net.Uri;
import android.os.Bundle;
import android.support.v4.app.ListFragment;
import android.util.DisplayMetrics;
import android.view.View;
import android.widget.AbsListView;
import android.widget.ArrayAdapter;
import android.widget.ImageView;
import android.widget.ListView;

import com.ddesign.foyer.HTTP.in.ProduitsListDownloader;
import com.ddesign.foyer.HTTP.in.DownloadListener;
import com.ddesign.foyer.R;
import com.ddesign.foyer.dummy.CommandItem;
import com.ddesign.foyer.dummy.Content;
import com.ddesign.foyer.dummy.Item;
import com.ddesign.foyer.dummy.ProduitItem;
import com.nostra13.universalimageloader.core.DisplayImageOptions;
import com.nostra13.universalimageloader.core.ImageLoader;
import com.nostra13.universalimageloader.core.ImageLoaderConfiguration;

import java.util.List;

/**
 * A list fragment representing a list of Items. This fragment
 * also supports tablet devices by allowing list items to be given an
 * 'activated' state upon selection. This helps indicate which item is
 * currently being viewed in a {@link ItemDetailFragment}.
 * <p/>
 * Activities containing this fragment MUST implement the {@link Callbacks}
 * interface.
 */
public class ItemListFragment extends ListFragment implements DownloadListener {

    /**
     * The serialization (saved instance state) Bundle key representing the
     * activated item position. Only used on tablets.
     */
    private static final String STATE_ACTIVATED_POSITION = "activated_position";

    /**
     * The fragment's current callback object, which is notified of list item
     * clicks.
     */
    private Callbacks mCallbacks = sDummyCallbacks;

    /**
     * The current activated item position. Only used on tablets.
     */
    private int mActivatedPosition = ListView.INVALID_POSITION;

    /**
     * A callback interface that all activities containing this fragment must
     * implement. This mechanism allows activities to be notified of item
     * selections.
     */
    private ProduitsListDownloader plDownloader;
    private List<Item> currentItems;

    public interface Callbacks {
        /**
         * Callback for when an item has been selected.
         */
        public void onItemSelected(String id);
    }

    /**
     * A dummy implementation of the {@link Callbacks} interface that does
     * nothing. Used only when this fragment is not attached to an activity.
     */
    private static Callbacks sDummyCallbacks = new Callbacks() {
        @Override
        public void onItemSelected(String id) {
        }
    };

    /**
     * Mandatory empty constructor for the fragment manager to instantiate the
     * fragment (e.g. upon screen orientation changes).
     */
    public ItemListFragment() {
    }

    @Override
    public void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        currentItems = Content.PRODUIT_ITEMS;
        if(Content.PRODUIT_ITEMS.size() == 0) {
            plDownloader = new ProduitsListDownloader(
                    getString(R.string.api_url) + getString(R.string.api_products),
                    getString(R.string.api_url) + getString(R.string.api_images),
                    this);
            plDownloader.execute();
        }else {
            updateView();
        }
    }

    public void updateView(){
        if(currentItems.equals(Content.PRODUIT_ITEMS)){
            ArrayAdapter<ProduitItem> produits = new ArrayAdapter<ProduitItem>(
                    getActivity(),
                    android.R.layout.simple_list_item_activated_1,
                    android.R.id.text1,
                    (List<ProduitItem>)(List<?>) currentItems);
            setListAdapter(produits);
        }else{
            ArrayAdapter<CommandItem> commands = new ArrayAdapter<CommandItem>(
                    getActivity(),
                    android.R.layout.simple_list_item_activated_1,
                    android.R.id.text1,
                    (List<CommandItem>)(List<?>) currentItems);
            setListAdapter(commands);
        }
    }

    public void switchList(){
        System.out.println("switch list");
        if(currentItems.equals(Content.PRODUIT_ITEMS))
            currentItems = Content.COMMAND_ITEMS;
        else
            currentItems = Content.PRODUIT_ITEMS;
        updateView();
    }
    @Override
    public void onViewCreated(View view, Bundle savedInstanceState) {
        super.onViewCreated(view, savedInstanceState);

        ImageView imageView = new ImageView(getActivity());
        imageView.setImageResource(R.drawable.cover);

        AbsListView.LayoutParams params
                = new AbsListView.LayoutParams(AbsListView.LayoutParams.FILL_PARENT,
                AbsListView.LayoutParams.WRAP_CONTENT);

        imageView.setAdjustViewBounds(true);
        int margin = (int)(getResources().getDimension(R.dimen.cover_top_margin));
        view.setPadding(0, dpToPx(margin), 0, 0);
        //imageView.setScaleType(ImageView.ScaleType.FIT_START);

        // Prepare image loader settings with caching
        this.getListView().addHeaderView(imageView);
        ImageLoader imageLoader = ImageLoader.getInstance();
        DisplayImageOptions defaultOptions = new DisplayImageOptions.Builder().cacheInMemory(true)
                .cacheOnDisk(true)
                .build();
        ImageLoaderConfiguration config = new ImageLoaderConfiguration.Builder(this.getContext())
                .defaultDisplayImageOptions(defaultOptions).build();
        imageLoader.init(config);

        // Restore the previously serialized activated item position.
        if (savedInstanceState != null
                && savedInstanceState.containsKey(STATE_ACTIVATED_POSITION)) {
            setActivatedPosition(savedInstanceState.getInt(STATE_ACTIVATED_POSITION));
        }
    }

    private int dpToPx(int dp) {
        DisplayMetrics displayMetrics = getContext().getResources().getDisplayMetrics();
        return (int)((dp * displayMetrics.density) + 0.5);
    }

    @Override
    public void onAttach(Activity activity) {
        super.onAttach(activity);

        // Activities containing this fragment must implement its callbacks.
        if (!(activity instanceof Callbacks)) {
            throw new IllegalStateException("Activity must implement fragment's callbacks.");
        }

        mCallbacks = (Callbacks) activity;
    }

    @Override
    public void onDetach() {
        super.onDetach();

        // Reset the active callbacks interface to the dummy implementation.
        mCallbacks = sDummyCallbacks;
    }

    @Override
    public void onListItemClick(ListView listView, View view, int position, long id) {
        super.onListItemClick(listView, view, position, id);

        // Notify the active callbacks interface (the activity, if the
        // fragment is attached to one) that an item has been selected.
        position--;
        if(position<0) {
            Intent browserIntent = new Intent(Intent.ACTION_VIEW, Uri.parse(getString(R.string.facebook_url )));
            startActivity(browserIntent);
            return;
        }
        mCallbacks.onItemSelected(Content.PRODUIT_ITEMS.get(position).getId());
    }

    @Override
    public void onSaveInstanceState(Bundle outState) {
        super.onSaveInstanceState(outState);
        if (mActivatedPosition != ListView.INVALID_POSITION) {
            // Serialize and persist the activated item position.
            outState.putInt(STATE_ACTIVATED_POSITION, mActivatedPosition);
        }
    }

    /**
     * Turns on activate-on-click mode. When this mode is on, list items will be
     * given the 'activated' state when touched.
     */
    public void setActivateOnItemClick(boolean activateOnItemClick) {
        // When setting CHOICE_MODE_SINGLE, ListView will automatically
        // give items the 'activated' state when touched.
        getListView().setChoiceMode(activateOnItemClick
                ? ListView.CHOICE_MODE_SINGLE
                : ListView.CHOICE_MODE_NONE);
    }

    private void setActivatedPosition(int position) {
        if (position == ListView.INVALID_POSITION) {
            getListView().setItemChecked(mActivatedPosition, false);
        } else {
            getListView().setItemChecked(position, true);
        }

        mActivatedPosition = position;
    }
}
