#include <stdio.h>
#include <string.h>
#include <stdlib.h>

#define BUFFER_SIZE 16

void process_user_input() {
    char buffer[BUFFER_SIZE];
    char *result;
    
    printf("Enter your username (max %d characters): ", BUFFER_SIZE - 1);
    
    // Use fgets instead of gets, limiting input to buffer size-1 (leaving room for null terminator)
    result = fgets(buffer, BUFFER_SIZE, stdin);
    
    if (result == NULL) {
        printf("Error reading input.\n");
        return;
    }
    
    // Remove newline character if present
    size_t len = strlen(buffer);
    if (len > 0 && buffer[len-1] == '\n') {
        buffer[len-1] = '\0';
    }
    
    // Check if input was too long (if fgets filled the buffer completely, it might have truncated input)
    if (len == BUFFER_SIZE - 1 && buffer[BUFFER_SIZE-2] != '\n') {
        // Clear the input buffer to prevent overflow on subsequent reads
        int c;
        while ((c = getchar()) != '\n' && c != EOF);
        
        printf("Warning: Input too long, truncated to %d characters.\n", BUFFER_SIZE - 2);
    }
    
    printf("Hello, %s! Your input has been processed.\n", buffer);
}

int main(int argc, char *argv[]) {
    printf("Welcome to the User Processing System (Secure Version)\n");
    process_user_input();
    printf("Program execution completed normally.\n");
    return 0;
}