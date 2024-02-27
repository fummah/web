import React, { useState } from 'react';
import { Modal, Text, View, TouchableOpacity, StyleSheet } from 'react-native';
import { useNavigation } from '@react-navigation/native';
import { COLORS } from '../../constants/theme';

const SuccessRegistrationAlert = ({message}) => {
const navigation = useNavigation();
  const [modalVisible, setModalVisible] = useState(true);

  const handleToLogin = () =>{
    navigation.navigate('Auth', {
      screen: 'Signin',
    });
  }
  return (
    <Modal
      animationType="fade"
      transparent={true}
      visible={modalVisible}
      onRequestClose={() => {}}
    >
      <View style={styles.centeredView}>
        <View style={styles.modalView}>
          <Text style={styles.successText}>{message}</Text>
          
          <TouchableOpacity
            style={styles.closeButton}
            onPress={handleToLogin}
          >
            <Text style={styles.closeButtonText}>Go to Login</Text>
          </TouchableOpacity>
      
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
  successText: {
    marginBottom: 20,
    textAlign: 'center',
    color: COLORS.green,
    fontWeight: 'normal',
    fontSize: 16
  },
  closeButton: {
    backgroundColor: COLORS.green,
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

export default SuccessRegistrationAlert;
