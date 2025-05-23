package com.example.vulnerable;

import java.io.Serializable;

public class UserPreference implements Serializable {
    private static final long serialVersionUID = 1L;
    
    private String username;
    private String theme;
    private int itemsPerPage;
    
    public UserPreference(String username) {
        this.username = username;
        this.theme = "default";
        this.itemsPerPage = 10;
    }
    
    public String getUsername() {
        return username;
    }
    
    public void setTheme(String theme) {
        this.theme = theme;
    }
    
    public String getTheme() {
        return theme;
    }
    
    public void setItemsPerPage(int itemsPerPage) {
        this.itemsPerPage = itemsPerPage;
    }
    
    public int getItemsPerPage() {
        return itemsPerPage;
    }
    
    public void applyPreferences() {
        System.out.println("Applying preferences for user " + username + 
                          ": theme=" + theme + ", itemsPerPage=" + itemsPerPage);
    }
}