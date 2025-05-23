package com.example.client;

import java.io.*;
import java.net.Socket;

// Import the vulnerable version of UserPreference for testing with vulnerable server
// For testing with secure server, you'd need to import com.example.secure.UserPreference instead
import com.example.vulnerable.UserPreference;

public class LegitimateClient {
    public static void main(String[] args) {
        // Use this to send to either the vulnerable (9876) or secure (9877) server
        int port = 9876; // Change to 9877 to test secure server
        
        try {
            // Create a normal UserPreference object
            UserPreference userPref = new UserPreference("legitimate_user");
            userPref.setTheme("dark");
            userPref.setItemsPerPage(25);
            
            System.out.println("Sending legitimate UserPreference to server on port " + port);
            
            // Connect to server and send the object
            try (Socket socket = new Socket("localhost", port);
                 ObjectOutputStream oos = new ObjectOutputStream(socket.getOutputStream())) {
                
                oos.writeObject(userPref);
                System.out.println("Object sent successfully");
                
                // Read response
                try (BufferedReader in = new BufferedReader(new InputStreamReader(socket.getInputStream()))) {
                    String line = in.readLine();
                    if (line != null) {
                        System.out.println("Server response: " + line);
                    }
                }
            }
            
        } catch (Exception e) {
            System.err.println("Error in client:");
            e.printStackTrace();
        }
    }
}