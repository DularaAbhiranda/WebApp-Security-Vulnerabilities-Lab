package com.example.secure;

import java.io.*;

public class UserPreference implements Serializable {
    public static final long serialVersionUID = 2L;
    
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
        if (theme == null || !isValidTheme(theme)) {
            throw new IllegalArgumentException("Invalid theme");
        }
        this.theme = theme;
    }
    
    private boolean isValidTheme(String theme) {
        return theme.matches("^[a-zA-Z0-9_-]+$") && 
               (theme.equals("light") || theme.equals("dark") || theme.equals("default"));
    }
    
    public String getTheme() {
        return theme;
    }
    
    public void setItemsPerPage(int itemsPerPage) {
        if (itemsPerPage < 1 || itemsPerPage > 100) {
            throw new IllegalArgumentException("Items per page must be between 1 and 100");
        }
        this.itemsPerPage = itemsPerPage;
    }
    
    public int getItemsPerPage() {
        return itemsPerPage;
    }
    
    public void applyPreferences() {
        System.out.println("Applying preferences for user " + username + 
                          ": theme=" + theme + ", itemsPerPage=" + itemsPerPage);
    }
    
    // Custom readObject method to add additional validation
    private void readObject(java.io.ObjectInputStream in) throws IOException, ClassNotFoundException {
        in.defaultReadObject();
        
        // Validate state after deserialization
        if (username == null || username.isEmpty()) {
            throw new InvalidObjectException("Username cannot be empty");
        }
        
        if (!isValidTheme(theme)) {
            throw new InvalidObjectException("Invalid theme");
        }
        
        if (itemsPerPage < 1 || itemsPerPage > 100) {
            throw new InvalidObjectException("Invalid itemsPerPage");
        }
    }
}