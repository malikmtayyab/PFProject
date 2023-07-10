import logo from './logo.svg';
import './App.css';
import { BrowserRouter,Routes,Route } from 'react-router-dom';
import Signup_page from './components/Signup_page';
import Login from './components/Login';

function App() {
  return (
  <div>
    
  

  <Routes>
    <Route exact path='/' element={<Login/>} />
    <Route exact path='/signup' element={<Signup_page/>}/>
  </Routes>
  

  </div>
  );
}

export default App;
