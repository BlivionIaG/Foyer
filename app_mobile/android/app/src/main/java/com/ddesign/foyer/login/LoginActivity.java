package com.ddesign.foyer.login;

import android.app.Dialog;
import android.content.Intent;
import android.os.Bundle;
import android.support.v7.app.AppCompatActivity;
import android.view.View;
import android.view.Window;
import android.widget.Button;
import android.widget.CheckBox;
import android.widget.EditText;
import android.widget.ProgressBar;
import android.widget.Spinner;
import android.widget.TextView;

import com.ddesign.foyer.JSON.Save;
import com.ddesign.foyer.R;
import com.ddesign.foyer.item.ItemListActivity;

import org.json.JSONObject;
import org.json.simple.parser.JSONParser;

public class LoginActivity extends AppCompatActivity implements AuthListener {

    private Intent intent;
    private Save save;
    private CheckBox checkBox;
    private EditText editText_pswd, editText_login;
    private Button button_connect;
    private AuthValidator authValidator;
    private LoginActivity context;
    private Dialog dialog;
    private TextView dialogMessage, dialogTitle;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_login);

        button_connect = (Button) findViewById(R.id.button_connect);
        editText_login = (EditText) findViewById(R.id.editText_login);
        editText_pswd = (EditText) findViewById(R.id.editText_pswd);
        checkBox = (CheckBox) findViewById(R.id.checkBox);
        intent = new Intent(this, ItemListActivity.class);
        context = this;
        dialog = new Dialog(this);
        dialog.requestWindowFeature(Window.FEATURE_NO_TITLE);
        dialog.setContentView(R.layout.cas_dialog);
        ((TextView) dialog.findViewById(R.id.cas_dialog_title)).setText(R.string.cas_dialog_title_loading);
        dialog.setCancelable(true);
        dialogTitle = (TextView) dialog.findViewById(R.id.cas_dialog_title);
        dialogMessage = (TextView) dialog.findViewById(R.id.cas_dialog_message);
        Button dialogButton = (Button) dialog.findViewById(R.id.cas_dialog_button);
        dialogButton.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                if(((TextView) dialog.findViewById(R.id.cas_dialog_title)).getText().toString()
                        == context.getString(R.string.cas_dialog_title_loading)) {
                    authValidator.cancel(true);
                }
                dialog.dismiss();
            }
        });

        save = new Save(this);
        save.read();

        if(save.getUsername().length() > 0 && save.getPassword().length() > 0){
            editText_login.setText(save.getUsername());
            editText_pswd.setText(save.getPassword());
            checkBox.setChecked(save.isRemember());
            if(save.getLogin_auto() == true)
                validateLogin(save.getUsername(),save.getPassword());
        }


        button_connect.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                validateLogin(editText_login.getText().toString(), editText_pswd.getText().toString());
            }
        });
    }


    private void validateLogin(String username, String password){
        if(dialog.isShowing())
            dialog.dismiss();
        dialogTitle.setText(context.getString(R.string.cas_dialog_title_loading));
        dialogMessage.setText(context.getString(R.string.cas_dialog_loading_message));
        ProgressBar progressBar = (ProgressBar) dialog.findViewById(R.id.progress_bar);
        progressBar.setVisibility(View.VISIBLE);
        progressBar.setEnabled(true);
        authValidator = new AuthValidator(username,password);
        authValidator.addListener(context);
        authValidator.execute();
        dialog.show();
    }
    private void saveInfo(){

    }

    /*
    error :
        0 -> bad login/password
        1 -> connection trouble

     */
    private void showErrorDialog(int error){
        if(dialog.isShowing()) {
            dialog.dismiss();

            dialogTitle.setText(context.getString(R.string.cas_dialog_title_error));
            dialogMessage.setText(context.getString(R.string.cas_dialog_net_error_message));
            ProgressBar progressBar = (ProgressBar) dialog.findViewById(R.id.progress_bar);
            progressBar.setVisibility(View.GONE);
            progressBar.setEnabled(false);
        }
        dialogTitle.setText(R.string.cas_dialog_title_error);
        switch(error){
            case 0:
                dialogMessage.setText(R.string.cas_dialog_bad_login_message);
                break;
            case 1:
                dialogMessage.setText(R.string.cas_dialog_net_error_message);
                break;
            default:
                dialogMessage.setText(R.string.cas_dialog_unknown_error_message);
                break;
        }
        dialog.show();
    }

    
    @Override
    public void onResult(int state){

        if(authValidator.getResult()) {
            save.setRemember(checkBox.isChecked());
            save.setUsername(editText_login.getText().toString());
            save.setPassword(editText_pswd.getText().toString());
            save.write();
           // intent.putExtra("save", save.getJSONObject());
            saveInfo();
            startActivity(intent);
            this.finishActivity(RESULT_OK);
        }else{
            showErrorDialog(state);
        }
    }
}
