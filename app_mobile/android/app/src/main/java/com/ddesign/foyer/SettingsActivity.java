package com.ddesign.foyer;

import android.app.Dialog;
import android.os.Bundle;
import android.provider.Settings;
import android.support.design.widget.Snackbar;
import android.support.v7.app.AppCompatActivity;
import android.text.Html;
import android.view.MenuItem;
import android.view.View;
import android.widget.Button;
import android.widget.CompoundButton;
import android.widget.Switch;
import android.widget.TextView;
import android.widget.Toast;

import com.ddesign.foyer.JSON.Save;
import com.nostra13.universalimageloader.core.ImageLoader;

import org.json.simple.JSONObject;

import java.io.File;

public class SettingsActivity extends AppCompatActivity implements CompoundButton.OnCheckedChangeListener {

    private Save save;
    private Switch auto_login, startup_service;
    private TextView an;
    private Dialog dialog;
    private Button dialog_button, cach_button, data_button;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_settings);
        getSupportActionBar().setHomeButtonEnabled(true);
        save = new Save(this);
        save.read();
        if(save.getUsername().length() == 0){
            return;
        }

        auto_login = (Switch) findViewById(R.id.switch_autolog);
        an = (TextView) findViewById(R.id.alexandre_nicolas);

        dialog = new Dialog(this);
        dialog.setContentView(R.layout.ee_dialog);
        an.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                dialog.show();

            }
        });
        dialog_button = (Button) dialog.findViewById(R.id.ee_dialog_button);
        dialog_button.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                dialog.dismiss();
            }
        });

        auto_login.setChecked(save.getLogin_auto());
        auto_login.setOnCheckedChangeListener(this);
        startup_service = (Switch) findViewById(R.id.switch_startup);
        startup_service.setChecked(save.isStartup_service());
        startup_service.setOnCheckedChangeListener(this);

        data_button = (Button) findViewById(R.id.button_data);
        cach_button = (Button) findViewById(R.id.button_cach);
        data_button.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                save.delete();
                Snackbar.make(v, "Votre sauvegarde viens d'être supprimée.",
                        Snackbar.LENGTH_LONG).setAction("Action", null).show();
                /* faire une alert de déco et d'avertissement */
            }
        });
        cach_button.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                ImageLoader il = ImageLoader.getInstance();
                il.clearDiskCache();
                il.clearMemoryCache();
                Snackbar.make(v, "Le cache de l'application est maintenant vide.",
                        Snackbar.LENGTH_LONG).setAction("Action", null).show();
            }
        });

    }

    @Override
    public boolean onOptionsItemSelected(MenuItem item) {
        // Handle item selection
        switch (item.getItemId()) {
            case android.R.id.home:
                this.finish();
                return true;
            default:
                return super.onOptionsItemSelected(item);
        }
    }

    @Override
    public void onCheckedChanged(CompoundButton buttonView, boolean isChecked) {

        if(buttonView.equals(auto_login)){
            save.setLogin_auto(isChecked);
        }else if(buttonView.equals(startup_service)){
            save.setStartup_service(isChecked);
        }else{
            return;
        }

        save.write();

    }
}
