import { useEffect } from 'react';
import PixelSnow from './components/PixelSnow';
import Navbar from './components/Navbar';
import Hero from './components/Hero';
import NoRestrictions from './components/NoRestrictions';
import HowItWorks from './components/HowItWorks';
import Features from './components/Features';
import ChatDemo from './components/ChatDemo';
import Disclaimer from './components/Disclaimer';
import CTA from './components/CTA';
import FAQ from './components/FAQ';
import SocialProof from './components/SocialProof';
import Footer from './components/Footer';
import './App.css';

function App() {
  useEffect(() => {
    const handleScroll = () => {
      const sections = document.querySelectorAll('.fade-section');
      const windowHeight = window.innerHeight;
      
      sections.forEach((section) => {
        const rect = section.getBoundingClientRect();
        const sectionTop = rect.top;
        const sectionHeight = rect.height;
        
        let opacity = 1;
        
        if (sectionTop < windowHeight * 0.3) {
          opacity = Math.max(0, (sectionTop + sectionHeight * 0.5) / (windowHeight * 0.5));
        }
        
        if (sectionTop > windowHeight * 0.7) {
          opacity = Math.max(0, 1 - (sectionTop - windowHeight * 0.7) / (windowHeight * 0.3));
        }
        
        section.style.opacity = Math.min(1, Math.max(0.1, opacity));
        section.style.transform = `translateY(${(1 - opacity) * 20}px)`;
      });
    };

    window.addEventListener('scroll', handleScroll);
    handleScroll();
    
    return () => window.removeEventListener('scroll', handleScroll);
  }, []);

  return (
    <div className="app">
      <div className="global-background">
        <PixelSnow
          color="#00d4ff"
          flakeSize={0.01}
          minFlakeSize={1.25}
          pixelResolution={200}
          speed={1.25}
          density={0.3}
          direction={125}
          brightness={1}
        />
      </div>
      
      <div className="content-wrapper">
        <Navbar />
        <div className="fade-section"><Hero /></div>
        <div className="fade-section"><NoRestrictions /></div>
        <div className="fade-section"><HowItWorks /></div>
        <div className="fade-section"><Features /></div>
        <div className="fade-section"><ChatDemo /></div>
        <div className="fade-section"><Disclaimer /></div>
        <div className="fade-section"><CTA /></div>
        <div className="fade-section"><FAQ /></div>
        <div className="fade-section"><SocialProof /></div>
        <Footer />
      </div>
    </div>
  );
}

export default App;
