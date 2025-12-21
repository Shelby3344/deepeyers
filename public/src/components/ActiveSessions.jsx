import { Activity, AlertTriangle } from 'lucide-react';
import './ActiveSessions.css';

const sessions = [
  { id: 'SES-001', target: '192.168.1.105', status: 'active', type: 'Shell', user: 'filipe' },
  { id: 'SES-002', target: '10.0.0.42', status: 'active', type: 'Meterpreter', user: 'filipe' },
  { id: 'SES-003', target: '172.16.0.15', status: 'idle', type: 'SSH', user: 'filipe' }
];

export default function ActiveSessions() {
  return (
    <section className="active-sessions">
      <div className="container">
        <div className="sessions-panel">
          <div className="panel-header">
            <div className="panel-title">
              <Activity size={20} className="panel-icon" />
              <span>Sessões Ativas</span>
            </div>
            <div className="panel-user mono">
              <span className="user-avatar">F</span>
              filipe (ADMIN)
            </div>
          </div>

          <div className="sessions-list mono">
            {sessions.map((s, i) => (
              <div key={i} className={`session-item ${s.status}`}>
                <div className="session-id">{s.id}</div>
                <div className="session-target">{s.target}</div>
                <div className="session-type">{s.type}</div>
                <div className={`session-status ${s.status}`}>
                  <span className="status-indicator"></span>
                  {s.status === 'active' ? 'Ativo' : 'Idle'}
                </div>
              </div>
            ))}
          </div>

          <div className="panel-footer">
            <div className="beta-warning">
              <AlertTriangle size={18} className="warning-icon" />
              <span>Status: BETA — funcionalidades instáveis</span>
            </div>
          </div>
        </div>
      </div>
    </section>
  );
}
