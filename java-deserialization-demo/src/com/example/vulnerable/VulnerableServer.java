package com.example.vulnerable;

import java.io.*;
import java.net.ServerSocket;
import java.net.Socket;

public class VulnerableServer {
    public static void main(String[] args) {
        int port = 9876;
        
        try (ServerSocket serverSocket = new ServerSocket(port)) {
            System.out.println("Vulnerable server started on port " + port);
            
            while (true) {
                try (Socket clientSocket = serverSocket.accept()) {
                    System.out.println("Client connected from " + clientSocket.getInetAddress());
                    
                    // VULNERABLE CODE: Deserializing data without validation
                    try (ObjectInputStream ois = new ObjectInputStream(clientSocket.getInputStream())) {
                        Object obj = ois.readObject();
                        System.out.println("Received object of type: " + obj.getClass().getName());
                        
                        // Process the object (assuming it's a UserPreference)
                        if (obj instanceof UserPreference) {
                            UserPreference userPref = (UserPreference) obj;
                            userPref.applyPreferences();
                            
                            // Send response
                            try (PrintWriter out = new PrintWriter(clientSocket.getOutputStream(), true)) {
                                out.println("Preferences updated for user: " + userPref.getUsername());
                            }
                        } else {
                            // This will still process any serialized object!
                            System.out.println("Warning: Received unexpected object type: " + obj.getClass().getName());
                            try (PrintWriter out = new PrintWriter(clientSocket.getOutputStream(), true)) {
                                out.println("Processed object of type: " + obj.getClass().getName());
                            }
                        }
                    } catch (ClassNotFoundException e) {
                        System.err.println("Error: Invalid object type");
                        e.printStackTrace();
                    } catch (Exception e) {
                        System.err.println("Error processing object:");
                        e.printStackTrace();
                    }
                } catch (IOException e) {
                    System.err.println("Error handling client connection");
                    e.printStackTrace();
                }
            }
        } catch (IOException e) {
            System.err.println("Could not start server on port " + port);
            e.printStackTrace();
        }
    }
}