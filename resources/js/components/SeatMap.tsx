import React, { useState, useEffect } from 'react';

interface Seat {
  id: number;
  seatNumber: number;
  row: number;
  column: number;
  columnLabel: string;
  position: string;
  isSelectable: boolean;
}

interface SeatMapData {
  area: {
    id: number;
    name: string;
    description: string;
    capacity: number;
    gender_type: string;
    floor: number;
    section: string;
    event_type: string;
  };
  location: {
    id: number;
    name: string;
    slug: string;
  };
  grid: {
    maxRow: number;
    maxColumn: number;
    totalSeats: number;
  };
  seats: Seat[];
}

interface SeatMapProps {
  areaId: number;
  onSeatClick?: (seat: Seat) => void;
  onSeatSelect?: (seatIds: number[]) => void;
  selectedSeats?: number[];
  allowMultiSelect?: boolean;
  readonly?: boolean;
  showLegend?: boolean;
  className?: string;
}

export const SeatMap: React.FC<SeatMapProps> = ({
  areaId,
  onSeatClick,
  onSeatSelect,
  selectedSeats = [],
  allowMultiSelect = false,
  readonly = false,
  showLegend = true,
  className = ''
}) => {
  const [seatMapData, setSeatMapData] = useState<SeatMapData | null>(null);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState<string | null>(null);
  const [localSelectedSeats, setLocalSelectedSeats] = useState<number[]>(selectedSeats);
  const [renderKey, setRenderKey] = useState(0);

  // Initialize local state from props only once
  useEffect(() => {
    if (selectedSeats && selectedSeats.length > 0) {
      setLocalSelectedSeats(selectedSeats);
    }
  }, []); // Empty dependency array - only run once on mount

  useEffect(() => {
    fetchSeatMapData();
  }, [areaId]);

  const fetchSeatMapData = async () => {
    try {
      setLoading(true);
      setError(null);
      
      const response = await fetch(`/api/seat-maps/${areaId}`);
      if (!response.ok) {
        throw new Error('Failed to fetch seat map data');
      }
      
      const data = await response.json();
      setSeatMapData(data);
    } catch (err) {
      setError(err instanceof Error ? err.message : 'An error occurred');
    } finally {
      setLoading(false);
    }
  };

  const getSeatColor = (seat: Seat): string => {
    if (localSelectedSeats.includes(seat.id)) {
      return 'bg-blue-500 text-white border-blue-600 shadow-md';
    }
    
    return 'bg-white text-gray-700 border-gray-300 hover:bg-green-50 hover:border-green-400 hover:text-green-700';
  };


  const handleSeatClick = (seat: Seat) => {
    if (readonly || !seat.isSelectable) return;

    console.log('🎫 Seat clicked:', seat.seatNumber, 'Current selection:', localSelectedSeats);

    if (onSeatClick) {
      onSeatClick(seat);
    }

    if (allowMultiSelect) {
      let newSelectedSeats: number[];
      if (localSelectedSeats.includes(seat.id)) {
        newSelectedSeats = localSelectedSeats.filter(id => id !== seat.id);
        console.log('🔵 Deselecting seat:', seat.seatNumber);
      } else {
        newSelectedSeats = [...localSelectedSeats, seat.id];
        console.log('🟢 Selecting seat:', seat.seatNumber);
      }
      console.log('📝 New selection:', newSelectedSeats);
      setLocalSelectedSeats(newSelectedSeats);
      setRenderKey(prev => prev + 1);
      console.log('🔄 State updated, renderKey:', renderKey + 1);
      if (onSeatSelect) {
        onSeatSelect(newSelectedSeats);
      }
    } else {
      const newSelectedSeats = [seat.id];
      console.log('🟢 Single select:', seat.seatNumber);
      setLocalSelectedSeats(newSelectedSeats);
      setRenderKey(prev => prev + 1);
      if (onSeatSelect) {
        onSeatSelect(newSelectedSeats);
      }
    }
  };


  if (loading) {
    return (
      <div className={`seat-map-container ${className}`}>
        <div className="flex items-center justify-center p-8">
          <div className="animate-spin rounded-full h-8 w-8 border-b-2 border-primary"></div>
          <span className="ml-2 text-muted-foreground">Loading seat map...</span>
        </div>
      </div>
    );
  }

  if (error) {
    return (
      <div className={`seat-map-container ${className}`}>
        <div className="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded">
          <strong>Error:</strong> {error}
        </div>
      </div>
    );
  }

  if (!seatMapData) {
    return (
      <div className={`seat-map-container ${className}`}>
        <div className="text-center p-8 text-muted-foreground">
          No seat map data available
        </div>
      </div>
    );
  }

      const { area, grid, seats } = seatMapData;

      // Debug: Log current state
      console.log('🎨 Rendering with selection:', localSelectedSeats, 'renderKey:', renderKey);
      console.log('📐 Grid dimensions:', grid.maxRow, 'rows ×', grid.maxColumn, 'columns');
      console.log('📏 Row width:', grid.maxColumn * 36, 'px (32px seat + 4px gap)');

      // Group seats by rows
      const seatsByRow = seats.reduce((acc, seat) => {
        if (!acc[seat.row]) {
          acc[seat.row] = [];
        }
        acc[seat.row].push(seat);
        return acc;
      }, {} as Record<number, Seat[]>);

  return (
    <div className={`seat-map-container ${className}`}>
          {/* Legend */}
          {showLegend && (
            <div className="mb-6 flex justify-center gap-8 text-sm">
              <div className="flex items-center gap-3">
                <div className="w-8 h-8 bg-white border-2 border-gray-300 rounded-lg shadow-sm flex items-center justify-center font-mono font-bold text-sm">1</div>
                <span className="text-foreground font-medium">Available</span>
              </div>
              <div className="flex items-center gap-3">
                <div className="w-8 h-8 bg-blue-500 border-2 border-blue-600 rounded-lg shadow-sm flex items-center justify-center font-mono font-bold text-sm text-white">1</div>
                <span className="text-foreground font-medium">Selected</span>
              </div>
            </div>
          )}

          {/* Seat Map */}
          <div className="seat-map-container bg-gradient-to-b from-gray-50 to-gray-100 rounded-xl p-2 shadow-lg h-full">
            <div className="w-full h-full overflow-auto">
              <div key={renderKey} className="seat-grid" style={{ transform: 'scale(0.75)', transformOrigin: 'left top', width: 'fit-content' }}>
            {/* Seat Grid */}
            {Array.from({ length: grid.maxRow }, (_, rowIndex) => {
              const rowNumber = rowIndex + 1;
              const rowSeats = seatsByRow[rowNumber] || [];
              
                  return (
                    <div key={rowNumber} className="seat-row flex gap-1 mb-2" style={{ width: `${grid.maxColumn * 36}px` }}>
                  {/* Seats */}
                  {Array.from({ length: grid.maxColumn }, (_, colIndex) => {
                    const columnNumber = colIndex + 1;
                    const seat = rowSeats.find(s => s.column === columnNumber);
                    
                        if (!seat) {
                          return (
                            <div key={colIndex} className="w-8 h-8"></div>
                          );
                        }

                    return (
                      <button
                        key={seat.id}
                        onClick={() => handleSeatClick(seat)}
                        disabled={readonly || !seat.isSelectable}
                        className={`
                          w-8 h-8 border-2 rounded-lg
                          transition-all duration-200 transform
                          ${!readonly && seat.isSelectable ? 'cursor-pointer hover:scale-110 hover:shadow-md' : 'cursor-not-allowed'}
                          flex items-center justify-center relative
                          shadow-sm font-mono font-bold
                          ${seat.seatNumber > 999 ? 'text-xs' : 'text-sm'}
                        `}
                        title={`Seat ${seat.seatNumber} ${localSelectedSeats.includes(seat.id) ? '(Selected)' : '(Available)'}`}
                        style={{
                          backgroundColor: localSelectedSeats.includes(seat.id) ? '#3b82f6' : '#ffffff',
                          borderColor: localSelectedSeats.includes(seat.id) ? '#2563eb' : '#d1d5db',
                          color: localSelectedSeats.includes(seat.id) ? '#ffffff' : '#374151',
                          minWidth: '32px',
                          minHeight: '32px',
                          maxWidth: '32px',
                          maxHeight: '32px',
                          boxSizing: 'border-box',
                          overflow: 'hidden'
                        }}
                        data-seat-id={seat.id}
                        data-selected={localSelectedSeats.includes(seat.id)}
                      >
                        <span className="leading-none">
                          {seat.seatNumber}
                        </span>
                      </button>
                    );
                  })}
                </div>
              );
            })}
          </div>
        </div>
      </div>

      {/* Selection Summary */}
      {allowMultiSelect && localSelectedSeats.length > 0 && (
        <div className="mt-6 bg-blue-50 border border-blue-200 rounded-lg p-4">
          <div className="flex items-center justify-between">
            <div>
              <p className="text-sm font-medium text-blue-900">
                ✅ {localSelectedSeats.length} seat{localSelectedSeats.length !== 1 ? 's' : ''} selected
              </p>
              <p className="text-xs text-blue-700 mt-1">
                Seats: {localSelectedSeats.map(id => {
                  const seat = seats.find(s => s.id === id);
                  return seat ? seat.seatNumber : id;
                }).join(', ')}
              </p>
            </div>
            <button 
              onClick={() => {
                setLocalSelectedSeats([]);
                setRenderKey(prev => prev + 1);
              }}
              className="text-xs text-blue-600 hover:text-blue-800 underline"
            >
              Clear Selection
            </button>
          </div>
        </div>
      )}

      {/* Testing Info */}
      <div className="mt-4 text-center text-xs text-muted-foreground">
        <p>💡 Click seats to select/deselect them. Selected seats will turn blue.</p>
        <p>Current selection: {localSelectedSeats.length} seats</p>
      </div>
    </div>
  );
};

export default SeatMap;
