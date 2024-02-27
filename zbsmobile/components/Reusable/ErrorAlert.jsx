import React, { useState } from 'react';
import { Modal, Text, View, TouchableOpacity, StyleSheet } from 'react-native';

const ErrorAlert = ({ message, onClose }) => {
  const [modalVisible, setModalVisible] = useState(true);

  return (
    <Modal
      animationType="fade"
      transparent={true}
      visible={modalVisible}
      onRequestClose={() => {
        setModalVisible(false);
        onClose();
      }}
    >
      <View style={styles.centeredView}>
        <View style={styles.modalView}>
          <Text style={styles.errorText}>{message}</Text>
          {/*
          <TouchableOpacity
            style={styles.closeButton}
            onPress={() => {
              setModalVisible(false);
              onClose();
            }}
          >
            <Text style={styles.closeButtonText}>Close</Text>
          </TouchableOpacity>
        */}
        </View>
      </View>
    </Modal>
  );
};

const styles = StyleSheet.create({
  centeredView: {
    flex: 1,
    justifyContent: 'center',
    alignItems: 'center',
    backgroundColor: 'rgba(0, 0, 0, 0.5)' // Semi-transparent black background
  },
  modalView: {
    backgroundColor: 'white',
    borderRadius: 10,
    padding: 20,
    alignItems: 'center',
    elevation: 5
  },
  errorText: {
    marginBottom: 20,
    textAlign: 'center',
    color: 'red',
    fontWeight: 'normal',
    fontSize: 16
  },
  closeButton: {
    backgroundColor: '#2196F3',
    borderRadius: 5,
    paddingVertical: 10,
    paddingHorizontal: 20
  },
  closeButtonText: {
    color: 'white',
    fontWeight: 'bold',
    fontSize: 16
  }
});

export default ErrorAlert;
