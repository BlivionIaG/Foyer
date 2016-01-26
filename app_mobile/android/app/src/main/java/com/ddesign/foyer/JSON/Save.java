package com.ddesign.foyer.JSON;

import android.content.Context;

import org.json.simple.JSONObject;
import org.json.simple.parser.JSONParser;
import org.json.simple.parser.ParseException;

import java.io.File;
import java.io.FileReader;
import java.io.FileWriter;
import java.io.IOException;

/**
 * Created by Alexandre on 28/11/2015.
 */
public class Save {

    public final static String FILE_NAME = "save";
    private String password, username;
    private boolean login_auto = false, remember = false, startup_service = true;
    private JSONParser parser;
    private String path;

    public Save(Context context){
        this.password = "";
        this.username = "";
        path = context.getFilesDir().getPath();
        this.parser = new JSONParser();
    }

    public Save(Context context, JSONObject object){
        this.password = (String) object.get("password");
        this.username = (String) object.get("username");
        this.path = (String) object.get("path");
        this.login_auto = (Boolean) object.get("login_auto");
        this.remember = (Boolean) object.get("remember");
        this.startup_service = (Boolean) object.get("startup_service");

        if(password == null || username == null || path == null){
            password = "";
            username = "";
            path = context.getFilesDir().getPath();
        }

        this.parser = new JSONParser();
    }

    public void write(){
        JSONObject obj = new JSONObject();
        if(password.length()>0)
            obj.put("password", this.password);
        if(username.length()>0)
            obj.put("username", this.username);

        obj.put("login_auto", this.login_auto);
        obj.put("remember", this.remember);
        obj.put("login_auto", this.login_auto);
        obj.put("startup_service",this.startup_service);

        try {
            FileWriter file = new FileWriter(path + FILE_NAME);
            file.write(obj.toJSONString());
            file.flush();
            file.close();

        } catch (IOException e) {
            e.printStackTrace();
        }
    }

    public void read(){
        Object obj = null;
        try {
            obj = parser.parse(new FileReader(path + FILE_NAME));
        } catch (IOException e) {
            e.printStackTrace();
        } catch (ParseException e) {
            e.printStackTrace();
        }
        if(obj != null) {
            JSONObject jsonObject = (JSONObject) obj;

            if(jsonObject.get("username") != null)
                this.username = (String) jsonObject.get("username");
            if(jsonObject.get("password") != null)
                this.password = (String) jsonObject.get("password");
            if(jsonObject.get("login_auto") != null)
                this.login_auto = (Boolean) jsonObject.get("login_auto");
            if(jsonObject.get("remember") != null)
                this.remember = (Boolean) jsonObject.get("remember");
            if(jsonObject.get("startup_service") != null)
                this.startup_service = (Boolean) jsonObject.get("startup_service");

        }else{
            System.out.println("Save_read problem");
        }

    }

    public void delete(){
        File file = new File(path + FILE_NAME);
        if(file.exists())
            file.delete();
    }

    public String getPassword() {
        return password;
    }

    public void setPassword(String password) {
        this.password = password;
    }

    public void setUsername(String username){
        this.username = username;
    }

    public String getUsername(){
        return this.username;
    }

    public void setLogin_auto(Boolean login_auto){
        this.login_auto = login_auto;
    }

    public boolean getLogin_auto(){
        return this.login_auto;
    }

    public String getPath() {
        return path;
    }

    public void setPath(String path) {
        this.path = path;
    }

    public boolean isRemember() {
        return remember;
    }

    public void setRemember(boolean remember) {
        this.remember = remember;
    }

    public boolean isStartup_service() {
        return startup_service;
    }

    public void setStartup_service(boolean startup_service) {
        this.startup_service = startup_service;
    }

    public JSONObject getJSONObject(){
        JSONObject object = new JSONObject();
        object.put("username", username);
        object.put("password", password);
        object.put("login_auto", login_auto);
        object.put("remember", remember);
        object.put("startup_service", startup_service);
        return object;
    }


}
