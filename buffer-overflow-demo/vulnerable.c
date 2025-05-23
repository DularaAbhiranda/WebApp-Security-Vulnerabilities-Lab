#include <stdio.h>
#include <string.h>
#include <stdlib.h>

void process_user_input() {
    char buffer[16]; // Fixed-size buffer of 16 bytes
    printf("Enter your username: ");
    gets(buffer); // Vulnerable function - no bounds checking
    printf("Hello, %s! Your input has been processed.\n", buffer);
}

int main(int argc, char *argv[]) {
    printf("Welcome to the User Processing System\n");
    process_user_input();
    printf("Program execution completed normally.\n");
    return 0;
}