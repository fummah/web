import React from 'react';
import { View, Text, StyleSheet } from 'react-native';
import { Ionicons } from '@expo/vector-icons'; // Example: Using Ionicons, but you can replace it with any icon library

const BadgeIcon = ({ iconName, badgeCount }) => {
  return (
    <View style={styles.container}>
      <Ionicons name={iconName} size={30} color="black" />
      {badgeCount > 0 && (
        <View style={styles.badgeContainer}>
          <Text style={styles.badge}>{badgeCount}</Text>
        </View>
      )}
    </View>
  );
};

const styles = StyleSheet.create({
  container: {
    position: 'relative',
  },
  badgeContainer: {
    position: 'absolute',
    top: -5, // Adjust this value to position the badge vertically
    right: -5, // Adjust this value to position the badge horizontally
    minWidth: 20,
    height: 20,
    borderRadius: 10,
    backgroundColor: 'red', // Adjust badge background color
    justifyContent: 'center',
    alignItems: 'center',
  },
  badge: {
    color: 'white', // Adjust badge text color
    fontSize: 12, // Adjust badge text size
    fontWeight: 'bold', // Adjust badge text weight
  },
});

export default BadgeIcon;
