package com.ddesign.foyer.item;

import android.app.Activity;
import android.app.Dialog;
import android.os.Bundle;
import android.support.design.widget.CollapsingToolbarLayout;
import android.support.v4.app.Fragment;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.view.Window;
import android.widget.ImageButton;
import android.widget.ImageView;
import android.widget.TextView;

import com.ddesign.foyer.R;
import com.ddesign.foyer.dummy.CommandItem;
import com.ddesign.foyer.dummy.Content;
import com.ddesign.foyer.dummy.ProduitItem;

import java.util.Vector;


/**
 * A fragment representing a single Item detail screen.
 * This fragment is either contained in a {@link ItemListActivity}
 * in two-pane mode (on tablets) or a {@link ProductItemDetailActivity}
 * on handsets.
 */
public class CommandItemDetailFragment extends Fragment {
    /**
     * The fragment argument representing the item ID that this fragment
     * represents.
     */
    public static final String ARG_ITEM_ID = "item_id";

    /**
     * The dummy content this fragment is presenting.
     */
    private CommandItem mItem;

    /**
     * Mandatory empty constructor for the fragment manager to instantiate the
     * fragment (e.g. upon screen orientation changes).
     */
    public CommandItemDetailFragment() {
    }

    @Override
    public void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        if (getArguments().containsKey(ARG_ITEM_ID)) {
            System.out.println("id : " + getArguments().getString(ARG_ITEM_ID));
            mItem = (CommandItem) Content.COMMAND_ITEMS.get(Integer.parseInt(getArguments().getString(ARG_ITEM_ID)));

            Activity activity = this.getActivity();
            CollapsingToolbarLayout appBarLayout = (CollapsingToolbarLayout) activity.findViewById(R.id.toolbar_layout);
            if (appBarLayout != null) {
                appBarLayout.setTitle(mItem.getContent());
            }
        }
    }

    @Override
    public void onResume(){
        super.onResume(); // ICI Y'A UN BUG !!
    }

    @Override
    public View onCreateView(LayoutInflater inflater, ViewGroup container,
                             Bundle savedInstanceState) {
        View rootView = inflater.inflate(R.layout.fragment_item_detail, container, false);

        // Show the dummy content as text in a TextView.
        if (mItem != null) {
            ((TextView) rootView.findViewById(R.id.item_price)).setText(getString(R.string.text_item_price) +
                    " " + mItem.getPrice() + getString(R.string.devise));
            TextView details = (TextView) rootView.findViewById(R.id.item_detail);
            String detailsText = "";
            Vector<ProduitItem> produitItems = ((CommandItem) Content.COMMAND_ITEMS.get(Integer.parseInt(mItem.getId()))).getProduitItems();
            for(ProduitItem produitItem : produitItems)
                detailsText += produitItem.getContent() + " " + produitItem.getPrice() + this.getString(R.string.devise) + "\n";
            details.setText(detailsText);
            ImageButton imageButton = (ImageButton) rootView.findViewById(R.id.imageButton);
            final Dialog dialog = new Dialog(getActivity());
            dialog.requestWindowFeature(Window.FEATURE_NO_TITLE);
            dialog.setContentView(R.layout.image_dialog);
            ImageView imageView = (ImageView) dialog.findViewById(R.id.image_popup);
            imageView.setImageDrawable(mItem.getDrawable());
            imageButton.setOnClickListener(new View.OnClickListener() {
                @Override
                public void onClick(View v) {
                    dialog.show();
                }
            });

           /* if(!mItem.isAvailable()) {
                TextView notAvailableText = (TextView) rootView.findViewById(R.id.item_not_available);
                notAvailableText.setVisibility(View.VISIBLE);
            }*/
        }

        return rootView;
    }
}
