import pyttsx3
import datetime
import webbrowser
import os
import threading
import time

class SimpleVoiceAssistant:
    def __init__(self):
        # Initialize text-to-speech engine
        self.engine = pyttsx3.init()
        self.setup_voice()
        self.name = "Sandy"
        self.running = True
        
    def setup_voice(self):
        """Configure the voice properties"""
        voices = self.engine.getProperty('voices')
        # Try to set female voice
        try:
            self.engine.setProperty('voice', voices[1].id if len(voices) > 1 else voices[0].id)
        except:
            self.engine.setProperty('voice', voices[0].id)
        
        self.engine.setProperty('rate', 170)  # Speed
        self.engine.setProperty('volume', 0.9)  # Volume
    
    def speak(self, text):
        """Convert text to speech"""
        print(f"\n{self.name}: {text}")
        self.engine.say(text)
        self.engine.runAndWait()
    
    def get_input(self, prompt="You: "):
        """Get text input from user"""
        return input(prompt).lower().strip()
    
    def process_command(self, command):
        """Process the command"""
        if not command:
            return True
        
        # Exit commands
        if any(word in command for word in ['exit', 'quit', 'goodbye', 'bye', 'stop']):
            self.speak("Goodbye! Have a great day!")
            return False
        
        # Greeting
        elif any(word in command for word in ['hello', 'hi', 'hey']):
            self.speak("Hello! How can I help you today?")
        
        # Time
        elif 'time' in command:
            current_time = datetime.datetime.now().strftime("%I:%M %p")
            self.speak(f"The current time is {current_time}")
        
        # Date
        elif 'date' in command or 'today' in command:
            current_date = datetime.datetime.now().strftime("%B %d, %Y")
            self.speak(f"Today is {current_date}")
        
        # Day
        elif 'day' in command:
            current_day = datetime.datetime.now().strftime("%A")
            self.speak(f"Today is {current_day}")
        
        # Dental clinic - Appointment booking
        elif 'appointment' in command or 'book' in command or 'schedule' in command:
            self.speak("I can help you book an appointment. What date would you prefer?")
            date_response = self.get_input()
            self.speak(f"Great! What time works best for you?")
            time_response = self.get_input()
            self.speak(f"Perfect! Your appointment is booked for {date_response} at {time_response}. May I have your name please?")
            name = self.get_input()
            self.speak(f"Thank you {name}. Your appointment is confirmed. You'll receive a confirmation email shortly.")
        
        # Emergency
        elif 'emergency' in command or 'urgent' in command or 'pain' in command:
            self.speak("For dental emergencies, please call our emergency line at 1-800-DENTIST immediately. If this is a life-threatening emergency, please call 911.")
        
        # Hours
        elif 'hours' in command or 'open' in command or 'timing' in command:
            self.speak("We are open Monday to Friday from 9 AM to 6 PM, and Saturdays from 10 AM to 4 PM. We're closed on Sundays and major holidays.")
        
        # Location
        elif 'location' in command or 'address' in command or 'where' in command:
            self.speak("We are located at 123 Main Street, Vancouver, BC. There is free parking available.")
        
        # Insurance
        elif 'insurance' in command:
            self.speak("We accept most major insurance plans including Aetna, Blue Cross, Cigna, and Delta Dental. Please bring your insurance card to your appointment.")
        
        # Cost/Price
        elif 'cost' in command or 'price' in command or 'fee' in command:
            self.speak("Costs vary depending on the treatment. A basic cleaning starts at $120. Would you like to speak with our billing department for specific pricing?")
        
        # Services
        elif 'service' in command or 'treatment' in command:
            self.speak("We offer general dentistry, cosmetic dentistry, orthodontics, root canals, teeth whitening, and dental implants. What service are you interested in?")
        
        # Dentist
        elif 'dentist' in command or 'doctor' in command:
            self.speak("Our team includes Dr. Smith with 15 years of experience and Dr. Johnson specializing in cosmetic dentistry. Both are accepting new patients.")
        
        # Cancel appointment
        elif 'cancel' in command:
            self.speak("I can help you cancel your appointment. May I have your name and appointment date please?")
            cancel_info = self.get_input()
            self.speak(f"Your appointment has been cancelled. Would you like to reschedule?")
        
        # Search web
        elif 'search' in command or 'google' in command:
            search_query = command.replace('search', '').replace('google', '').strip()
            if search_query:
                self.speak(f"Searching for {search_query}")
                webbrowser.open(f"https://www.google.com/search?q={search_query}")
            else:
                self.speak("What would you like me to search for?")
        
        # Open YouTube
        elif 'youtube' in command:
            self.speak("Opening YouTube")
            webbrowser.open("https://www.youtube.com")
        
        # Help
        elif 'help' in command or 'what can you do' in command:
            self.speak("I can help you with: booking appointments, emergency assistance, clinic hours and location, insurance queries, pricing information, and general questions about our services. I can also tell you the time, date, search the web, and more. What would you like help with?")
        
        # Default response
        else:
            self.speak("I'm not sure how to help with that. You can ask me about appointments, hours, location, services, or say 'help' to hear what I can do.")
        
        return True
    
    def run(self):
        """Main loop for the assistant"""
        print("="*50)
        print("Simple Voice Assistant - Type-Based Version")
        print("="*50)
        print("\nCommands you can try:")
        print("  - Book an appointment")
        print("  - What are your hours?")
        print("  - What's the time?")
        print("  - Search for dental care tips")
        print("  - Help")
        print("  - Exit")
        print("\n" + "="*50 + "\n")
        
        self.speak(f"Hello! I'm {self.name}, your virtual receptionist at Vancouver Dental Clinic. How may I assist you today?")
        
        while self.running:
            try:
                command = self.get_input("\nYou: ")
                if not self.process_command(command):
                    break
            except KeyboardInterrupt:
                print("\n")
                self.speak("Goodbye!")
                break
            except Exception as e:
                print(f"Error: {e}")
                self.speak("I encountered an error. Let's try again.")


# Main execution
if __name__ == "__main__":
    assistant = SimpleVoiceAssistant()
    assistant.run()