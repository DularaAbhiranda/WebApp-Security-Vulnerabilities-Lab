package com.example.secure;

import java.io.*;
import java.net.ServerSocket;
import java.net.Socket;
import java.util.*;

public class SecureServer {
    // Define a whitelist of trusted classes
    private static final Set<String> TRUSTED_CLASSES = new HashSet<>(Arrays.asList(
        "com.example.secure.UserPreference"
    ));
    
    // Custom ObjectInputStream that validates classes before deserialization
    private static class ValidatingObjectInputStream extends ObjectInputStream {
        public ValidatingObjectInputStream(InputStream in) throws IOException {
            super(in);
        }
        
        @Override
        protected Class<?> resolveClass(ObjectStreamClass desc) throws IOException, ClassNotFoundException {
            String className = desc.getName();
            
            System.out.println("Attempting to deserialize class: " + className);
            
            // Validate class against whitelist
            if (!TRUSTED_CLASSES.contains(className)) {
                throw new InvalidClassException("Unauthorized deserialization attempt", className);
            }
            
            return super.resolveClass(desc);
        }
    }
    
    public static void main(String[] args) {
        int port = 9877; // Different port for secure server
        
        try (ServerSocket serverSocket = new ServerSocket(port)) {
            System.out.println("Secure server started on port " + port);
            
            while (true) {
                try (Socket clientSocket = serverSocket.accept()) {
                    System.out.println("Client connected from " + clientSocket.getInetAddress());
                    
                    try {
                        // SECURE CODE: Using custom ObjectInputStream with validation
                        ValidatingObjectInputStream ois = new ValidatingObjectInputStream(clientSocket.getInputStream());
                        
                        try {
                            Object obj = ois.readObject();
                            System.out.println("Received trusted object of type: " + obj.getClass().getName());
                            
                            // Process the object (assuming it's a UserPreference)
                            if (obj instanceof UserPreference) {
                                UserPreference userPref = (UserPreference) obj;
                                userPref.applyPreferences();
                                
                                // Send response
                                try (PrintWriter out = new PrintWriter(clientSocket.getOutputStream(), true)) {
                                    out.println("Preferences updated for user: " + userPref.getUsername());
                                }
                            }
                        } catch (ClassNotFoundException | InvalidClassException e) {
                            System.err.println("Security violation: " + e.getMessage());
                            try (PrintWriter out = new PrintWriter(clientSocket.getOutputStream(), true)) {
                                out.println("Error: Unauthorized deserialization attempt");
                            }
                        } finally {
                            ois.close();
                        }
                    } catch (Exception e) {
                        System.err.println("Error processing client data");
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