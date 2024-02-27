import React from 'react';
import { ActivityIndicator, View, StyleSheet } from 'react-native';
import { COLORS } from '../../constants/theme';

const OverlayActivityIndicator = () => (
    <View style={styles.container}>
    <View style={styles.overlay}>
      {<ActivityIndicator size="large" color={COLORS.white} />}
    </View>
  </View>
);

const styles = StyleSheet.create({
    container: {
        position: 'relative',
        flex: 1 // Ensure the component takes the full available space
      },
      overlay: {
        position: 'absolute',
        top: 0,
        left: 0,
        right: 0,
        bottom: 0,
        backgroundColor: COLORS.green,
        justifyContent: 'center',
        alignItems: 'center',
        zIndex: 999 // Ensure the overlay is on top of other elements
      },
      loader: {
        borderWidth: 8,
        borderColor: '#f3f3f3', // Note: border color instead of solid
        borderTopColor: '#3498db', // Note: borderTopColor instead of solid
        borderRadius: 50,
        width: 50,
        height: 50,
        transform: [{ rotate: '360deg' }] // React Native's transform uses degrees directly
      }
});

export default OverlayActivityIndicator;
